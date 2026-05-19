<?php

namespace Tests\Feature;

use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Funding;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use ZipArchive;

class BigPayFormExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_contract_big_pay_form_can_be_exported_with_filled_values(): void
    {
        $authUser = User::factory()->create();

        $company = Company::create([
            'name' => '杭州测试科技有限公司',
            'telephone' => '0571-88886666',
        ]);

        $responsibleUser = User::factory()->create([
            'name' => '李测试',
            'company_id' => $company->id,
            'phone' => '13800001111',
        ]);

        BankAccount::create([
            'company_id' => $company->id,
            'name' => '中国银行杭州分行',
            'number' => '6222020202020202',
            'is_primary' => true,
        ]);

        $funding = Funding::create([
            'name' => '信息化专项经费',
            'code' => 'XM2026001',
        ]);

        $project = Project::create([
            'name' => '校园网络年费项目',
            'company_id' => $company->id,
        ]);

        $contract = Contract::create([
            'name' => '校园网络接入服务合同',
            'year' => 2026,
            'code' => 'HT-2026-001',
            'signing_date' => '2026-05-16',
            'user_id' => $responsibleUser->id,
            'amount' => 151200,
            'paid_amount' => 0,
            'company_id' => $company->id,
            'project_id' => $project->id,
            'funding_id' => $funding->id,
        ]);

        $response = $this->actingAs($authUser)->get(route('contracts.big-pay-form', $contract));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $tempFile = $response->baseResponse->getFile()->getPathname();

        $zip = new ZipArchive();
        $this->assertTrue($zip->open($tempFile) === true);

        $documentXml = $zip->getFromName('word/document.xml');
        $zip->close();

        $this->assertIsString($documentXml);
        $this->assertStringContainsString('合同编号：HT-2026-001', $documentXml);
        $this->assertStringContainsString('杭州测试科技有限公司', $documentXml);
        $this->assertStringContainsString('李测试', $documentXml);
        $this->assertStringContainsString('校园网络年费项目', $documentXml);
        $this->assertStringContainsString('校园网络接入服务合同', $documentXml);
        $this->assertStringContainsString('151200', $documentXml);
        $this->assertStringContainsString('拾伍万壹仟贰佰元整', $documentXml);
        $this->assertStringContainsString('信息化专项经费', $documentXml);
        $this->assertStringContainsString('XM2026001', $documentXml);
        $this->assertStringContainsString('中国银行杭州分行', $documentXml);
        $this->assertStringContainsString('6222020202020202', $documentXml);
    }

    public function test_contract_big_pay_form_can_be_exported_without_project(): void
    {
        $authUser = User::factory()->create();

        $company = Company::create([
            'name' => '杭州独立测试科技有限公司',
            'telephone' => '0571-12345678',
        ]);

        $responsibleUser = User::factory()->create([
            'name' => '王测试',
            'company_id' => $company->id,
            'phone' => '13900002222',
        ]);

        BankAccount::create([
            'company_id' => $company->id,
            'name' => '工商银行杭州分行',
            'number' => '9558800000000000',
            'is_primary' => true,
        ]);

        $funding = Funding::create([
            'name' => '独立经费',
            'code' => 'DL2026001',
        ]);

        $contract = Contract::create([
            'name' => '独立合同',
            'year' => 2026,
            'code' => 'HT-2026-099',
            'signing_date' => '2026-05-16',
            'user_id' => $responsibleUser->id,
            'amount' => 20000,
            'paid_amount' => 0,
            'company_id' => $company->id,
            'funding_id' => $funding->id,
        ]);

        $response = $this->actingAs($authUser)->get(route('contracts.big-pay-form', $contract));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $tempFile = $response->baseResponse->getFile()->getPathname();

        $zip = new ZipArchive();
        $this->assertTrue($zip->open($tempFile) === true);

        $documentXml = $zip->getFromName('word/document.xml');
        $zip->close();

        $this->assertIsString($documentXml);
        $this->assertStringContainsString('合同编号：HT-2026-099', $documentXml);
        $this->assertStringContainsString('杭州独立测试科技有限公司', $documentXml);
        $this->assertStringContainsString('王测试', $documentXml);
        $this->assertStringContainsString('独立合同', $documentXml);
        $this->assertStringContainsString('20000', $documentXml);
        $this->assertStringContainsString('贰万元整', $documentXml);
        $this->assertStringContainsString('独立经费', $documentXml);
        $this->assertStringContainsString('DL2026001', $documentXml);
        $this->assertStringContainsString('工商银行杭州分行', $documentXml);
        $this->assertStringContainsString('9558800000000000', $documentXml);
    }
}

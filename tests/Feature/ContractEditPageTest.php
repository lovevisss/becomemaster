<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Contract;
use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractEditPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_page_shows_big_pay_export_link_when_contract_has_project(): void
    {
        $company = Company::create([
            'name' => '测试公司',
        ]);

        $user = User::factory()->create([
            'company_id' => $company->id,
        ]);

        $status = Status::create([
            'name' => '完成',
        ]);

        $project = Project::create([
            'name' => '测试项目',
            'company_id' => $company->id,
        ]);

        $contract = Contract::create([
            'name' => '测试合同',
            'year' => 2026,
            'path' => 'contracts/test.docx',
            'signing_date' => '2026-05-16',
            'user_id' => $user->id,
            'amount' => 15000,
            'company_id' => $company->id,
            'status_id' => $status->id,
            'project_id' => $project->id,
        ]);

        $response = $this->get(route('contract.edit', $contract));

        $response->assertOk();
        $response->assertSee('导出大额申请单');
        $response->assertSee(route('contracts.big-pay-form', $contract->id), false);
    }

    public function test_edit_page_shows_big_pay_export_link_when_contract_has_no_project(): void
    {
        $company = Company::create([
            'name' => '测试公司B',
        ]);

        $user = User::factory()->create([
            'company_id' => $company->id,
        ]);

        $status = Status::create([
            'name' => '申请',
        ]);

        $contract = Contract::create([
            'name' => '未关联项目合同',
            'year' => 2026,
            'path' => 'contracts/no-project.docx',
            'signing_date' => '2026-05-16',
            'user_id' => $user->id,
            'amount' => 9800,
            'company_id' => $company->id,
            'status_id' => $status->id,
        ]);

        $response = $this->get(route('contract.edit', $contract));

        $response->assertOk();
        $response->assertSee('导出大额申请单');
        $response->assertSee(route('contracts.big-pay-form', $contract->id), false);
    }
}


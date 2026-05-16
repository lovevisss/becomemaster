<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContractStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_contract_can_be_created_with_uploaded_file(): void
    {
        Storage::fake('local');

        $company = Company::create([
            'name' => '测试公司A',
        ]);

        $user = User::factory()->create([
            'company_id' => $company->id,
        ]);

        $file = UploadedFile::fake()->create('contract.pdf', 32, 'application/pdf');

        $response = $this->post('/contract', [
            'name' => '测试合同',
            'year' => 2026,
            'signing_date' => '2026-05-14',
            'user_id' => $user->id,
            'amount' => 12000,
            'company_id' => $company->id,
            'path' => $file,
        ]);

        $response->assertCreated();

        $contract = Contract::query()->first();
        $this->assertNotNull($contract);
        $this->assertSame('测试合同', $contract->name);
        $this->assertNotEmpty($contract->path);

        Storage::disk('local')->assertExists('public/'.$contract->path);
    }

    public function test_contract_store_returns_validation_error_when_file_is_missing(): void
    {
        $company = Company::create([
            'name' => '测试公司B',
        ]);

        $user = User::factory()->create([
            'company_id' => $company->id,
        ]);

        $response = $this->post('/contract', [
            'name' => '测试合同无文件',
            'year' => 2026,
            'signing_date' => '2026-05-14',
            'user_id' => $user->id,
            'amount' => 1000,
            'company_id' => $company->id,
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => '请上传合同文件。',
            ]);
    }
}


<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Contract;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContractUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_contract_status_can_be_updated(): void
    {
        $company = Company::create([
            'name' => '测试公司',
        ]);

        $user = User::factory()->create([
            'company_id' => $company->id,
        ]);

        $originalStatus = Status::create([
            'name' => '申请',
        ]);

        $updatedStatus = Status::create([
            'name' => '完成',
        ]);

        $contract = Contract::create([
            'name' => '测试合同',
            'year' => 2026,
            'path' => 'contracts/original.pdf',
            'signing_date' => '2026-05-16',
            'user_id' => $user->id,
            'amount' => 32000,
            'company_id' => $company->id,
            'status_id' => $originalStatus->id,
        ]);

        $response = $this->put("/contract/{$contract->id}", [
            'name' => $contract->name,
            'year' => $contract->year,
            'code' => 'HT-2026-001',
            'signing_date' => $contract->signing_date,
            'user_id' => $user->id,
            'amount' => $contract->amount,
            'company_id' => $company->id,
            'status_id' => $updatedStatus->id,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('status_id', $updatedStatus->id);

        $this->assertDatabaseHas('contracts', [
            'id' => $contract->id,
            'status_id' => $updatedStatus->id,
            'code' => 'HT-2026-001',
        ]);
    }

    public function test_contract_update_rejects_invalid_status(): void
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
            'name' => '测试合同B',
            'year' => 2026,
            'path' => 'contracts/original-b.pdf',
            'signing_date' => '2026-05-16',
            'user_id' => $user->id,
            'amount' => 18000,
            'company_id' => $company->id,
            'status_id' => $status->id,
        ]);

        $response = $this->put("/contract/{$contract->id}", [
            'name' => $contract->name,
            'year' => $contract->year,
            'signing_date' => $contract->signing_date,
            'user_id' => $user->id,
            'amount' => $contract->amount,
            'company_id' => $company->id,
            'status_id' => 999999,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('contracts', [
            'id' => $contract->id,
            'status_id' => $status->id,
        ]);
    }

    public function test_contract_invoice_can_be_uploaded_on_update(): void
    {
        Storage::fake('local');

        $company = Company::create([
            'name' => '测试公司C',
        ]);

        $user = User::factory()->create([
            'company_id' => $company->id,
        ]);

        $status = Status::create([
            'name' => '完成',
        ]);

        $contract = Contract::create([
            'name' => '测试合同C',
            'year' => 2026,
            'path' => 'contracts/original-c.pdf',
            'signing_date' => '2026-05-16',
            'user_id' => $user->id,
            'amount' => 26800,
            'company_id' => $company->id,
            'status_id' => $status->id,
        ]);

        $invoice = UploadedFile::fake()->create('invoice.pdf', 24, 'application/pdf');

        $response = $this->post("/contract/{$contract->id}", [
            'name' => $contract->name,
            'year' => $contract->year,
            'signing_date' => $contract->signing_date,
            'user_id' => $user->id,
            'amount' => $contract->amount,
            'company_id' => $company->id,
            'status_id' => $status->id,
            'invoice' => $invoice,
        ], [
            'X-HTTP-Method-Override' => 'PUT',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure(['invoice']);

        $contract->refresh();

        $this->assertNotEmpty($contract->invoice);
        $this->assertSame($contract->invoice, $response->json('invoice'));
        Storage::disk('local')->assertExists('public/' . $contract->invoice);

        $this->get("/contract/{$contract->id}/edit")
            ->assertOk()
            ->assertSee(str_replace('/', '\\/', $contract->invoice), false);
    }
}

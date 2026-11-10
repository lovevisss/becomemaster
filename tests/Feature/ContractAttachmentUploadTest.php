<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContractAttachmentUploadTest extends TestCase
{
    use RefreshDatabase;

    private function contract(): Contract
    {
        $company = Company::create(['name' => '附件测试公司']);
        $user = User::factory()->create(['company_id' => $company->id]);

        return Contract::create([
            'name' => '附件测试合同',
            'year' => 2026,
            'signing_date' => '2026-05-16',
            'user_id' => $user->id,
            'amount' => 20000,
            'company_id' => $company->id,
        ]);
    }

    public function test_each_attachment_can_be_saved_independently(): void
    {
        Storage::fake('local');
        $contract = $this->contract();

        foreach (['path', 'consult', 'acceptance', 'invoice'] as $type) {
            $response = $this->postJson("/contract/{$contract->id}/attachments/{$type}", [
                'file' => UploadedFile::fake()->create("{$type}.pdf", 24, 'application/pdf'),
            ]);

            $response->assertOk()->assertJsonPath('type', $type);
            $path = $response->json('path');
            $this->assertSame($path, $contract->fresh()->{$type});
            $this->assertSame(basename($path), $response->json('name'));
            $this->assertStringEndsWith('/storage/'.$path, $response->json('download_url'));
            Storage::disk('local')->assertExists('public/'.$path);
        }

        $this->get("/contract/{$contract->id}/edit")
            ->assertOk()
            ->assertSee(str_replace('/', '\\/', $contract->fresh()->acceptance), false);
    }

    public function test_attachment_upload_rejects_missing_file_invalid_type_and_oversize(): void
    {
        Storage::fake('local');
        $contract = $this->contract();

        $this->postJson("/contract/{$contract->id}/attachments/acceptance")
            ->assertUnprocessable()
            ->assertJsonValidationErrors('file');

        $this->postJson("/contract/{$contract->id}/attachments/unknown", [
            'file' => UploadedFile::fake()->create('file.pdf', 1),
        ])->assertUnprocessable();

        $this->postJson("/contract/{$contract->id}/attachments/acceptance", [
            'file' => UploadedFile::fake()->create('large.pdf', 20481),
        ])->assertUnprocessable()->assertJsonValidationErrors('file');

        $this->assertNull($contract->fresh()->acceptance);
    }
}

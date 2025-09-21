<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PlacementTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ExcelImportFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_download_excel_template()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get(route('admin.placement-tests.download-template'));
        
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('content-disposition', 'attachment; filename="placement_test_questions_template.xlsx"');
    }

    /** @test */
    public function admin_can_import_questions_from_excel()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $placementTest = PlacementTest::factory()->create();
        
        // Create a fake Excel file
        Storage::fake('local');
        $file = UploadedFile::fake()->create('questions.xlsx', 100);
        
        $response = $this->actingAs($admin)->post(route('admin.placement-tests.import-questions', $placementTest), [
            'excel_file' => $file
        ]);
        
        $response->assertStatus(302); // Redirect back
        // Note: We can't fully test the import functionality without a real Excel file,
        // but we can test that the endpoint is accessible and handles the file upload
    }

    /** @test */
    public function import_fails_with_invalid_file_type()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $placementTest = PlacementTest::factory()->create();
        
        // Create a fake text file (invalid type)
        $file = UploadedFile::fake()->create('document.txt', 100);
        
        $response = $this->actingAs($admin)->post(route('admin.placement-tests.import-questions', $placementTest), [
            'excel_file' => $file
        ]);
        
        // Should fail validation
        $response->assertSessionHasErrors('excel_file');
    }

    /** @test */
    public function import_fails_with_file_too_large()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $placementTest = PlacementTest::factory()->create();
        
        // Create a fake file that's too large (3MB)
        $file = UploadedFile::fake()->create('large_file.xlsx', 3000);
        
        $response = $this->actingAs($admin)->post(route('admin.placement-tests.import-questions', $placementTest), [
            'excel_file' => $file
        ]);
        
        // Should fail validation
        $response->assertSessionHasErrors('excel_file');
    }

    /** @test */
    public function student_cannot_access_import_functionality()
    {
        $student = User::factory()->create(['role' => 'student']);
        $placementTest = PlacementTest::factory()->create();
        
        // Create a fake Excel file
        $file = UploadedFile::fake()->create('questions.xlsx', 100);
        
        $response = $this->actingAs($student)->post(route('admin.placement-tests.import-questions', $placementTest), [
            'excel_file' => $file
        ]);
        
        // Should be redirected to login or forbidden
        $response->assertStatus(403); // Forbidden
    }
}
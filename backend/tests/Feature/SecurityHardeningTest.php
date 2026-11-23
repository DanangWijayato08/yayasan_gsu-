<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('login');
        RateLimiter::clear('contact-submission');
    }

    public function test_admin_endpoint_requires_authentication(): void
    {
        $response = $this->getJson('/api/admin/dashboard');
        $response->assertStatus(401);
    }

    public function test_admin_endpoint_rejects_non_admin_user(): void
    {
        $regularUser = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($regularUser, 'sanctum')->getJson('/api/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_admin_endpoint_allows_admin_user(): void
    {
        $adminUser = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($adminUser, 'sanctum')->getJson('/api/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_login_rate_limiting(): void
    {
        RateLimiter::clear('login');

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/admin/login', [
                'email' => 'attacker@example.com',
                'password' => 'wrong-pass',
            ]);
        }

        // 6th attempt should be throttled (429)
        $response = $this->postJson('/api/admin/login', [
            'email' => 'attacker@example.com',
            'password' => 'wrong-pass',
        ]);

        $response->assertStatus(429);
    }

    public function test_contact_submission_rate_limiting(): void
    {
        RateLimiter::clear('contact-submission');

        for ($i = 0; $i < 6; $i++) {
            $this->postJson('/api/contact-messages', [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'message' => 'Hello test message',
            ]);
        }

        // 7th attempt should be throttled (429)
        $response = $this->postJson('/api/contact-messages', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello test message',
        ]);

        $response->assertStatus(429);
    }

    public function test_upload_controller_local_fallback(): void
    {
        Storage::fake('public');
        $adminUser = User::factory()->create(['role' => 'admin']);

        $file = UploadedFile::fake()->image('test-avatar.jpg', 200, 200);

        $response = $this->actingAs($adminUser, 'sanctum')
            ->postJson('/api/admin/upload', [
                'image' => $file,
                'folder' => 'uploads',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'bucket', 'path', 'url']);
    }

    public function test_singleton_settings_validation_and_update(): void
    {
        $adminUser = User::factory()->create(['role' => 'admin']);

        // Invalid update (invalid email in contact settings)
        $invalidResponse = $this->actingAs($adminUser, 'sanctum')
            ->putJson('/api/admin/contact-settings', [
                'email' => 'not-an-email',
            ]);
        $invalidResponse->assertStatus(422);

        // Valid update
        $validResponse = $this->actingAs($adminUser, 'sanctum')
            ->putJson('/api/admin/contact-settings', [
                'address' => 'Jl. Kebajikan No. 10',
                'email' => 'info@gsu.org',
                'phone' => '+62812345678',
            ]);
        $validResponse->assertStatus(200);
        $this->assertEquals('info@gsu.org', $validResponse->json('email'));
    }

    public function test_upload_controller_rejects_svg_xss_file(): void
    {
        Storage::fake('public');
        $adminUser = User::factory()->create(['role' => 'admin']);

        $svgFile = UploadedFile::fake()->createWithContent(
            'malicious.svg',
            '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'
        );

        $response = $this->actingAs($adminUser, 'sanctum')
            ->postJson('/api/admin/upload', [
                'image' => $svgFile,
                'folder' => 'uploads',
            ]);

        $response->assertStatus(422);
    }

    public function test_non_admin_cannot_login_via_admin_login(): void
    {
        User::factory()->create([
            'email' => 'member@example.com',
            'password' => 'password123',
            'role' => 'user',
        ]);

        $response = $this->postJson('/api/admin/login', [
            'email' => 'member@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(403);
    }

    public function test_public_gallery_show_hides_inactive_items(): void
    {
        $inactive = \App\Models\Gallery::create([
            'title' => 'Draft Gallery',
            'image' => 'https://example.com/draft.jpg',
            'is_active' => false,
        ]);

        $this->getJson("/api/galleries/{$inactive->id}")->assertStatus(404);
    }
}

<?php

namespace Tests\Unit;

use App\Enums\RoleToStatusMappings;
use App\Models\Doc;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DocControllerTest extends TestCase
{
    use RefreshDatabase {
        refreshDatabase as parentRefreshDatabase;
    }

    public function refreshDatabase()
    {
        $this->parentRefreshDatabase();

        $this->seedDatabase();
    }

    protected function seedDatabase()
    {
        Artisan::call('db:seed');
    }

    public function test_assign_reviewer_successfully()
    {
        $response = $this->post('/api/assign-reviewer', [
            'user_id' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => 1,
                'status' => 'Reviewing',
                'assigned_to' => 1
            ]);

        $doc = Doc::find($response->json()['id']);
        $this->assertEquals(RoleToStatusMappings::getEnumArray()['reviewer'], $doc->status);
    }

    public function test_assign_reviewer_no_document_available()
    {
        $response = $this->post('/api/assign-reviewer', [
            'user_id' => 10,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_assign_registrant_successfully()
    {
        $response = $this->post('/api/assign-registrant', [
            'user_id' => 3,
            'role' => 'registrant',
        ]);

        $response->assertStatus(200);
        $doc = Doc::find($response->json()['id']);
        $this->assertEquals(RoleToStatusMappings::getEnumArray()['registrant'], $doc->status);
    }

    public function test_assign_registrant_no_document_available()
    {
        $response = $this->post('/api/assign-registrant', [
            'user_id' => 11,
            'role' => 'registrant',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_get_assigned_docs_successfully()
    {
        $user = User::factory()->create();

        $this->post('/api/assign-reviewer', [
            'user_id' => $user->id,
        ]);

        $response = $this->get('/api/assigned-docs');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json());
    }

    public function test_get_assigned_docs_no_assigned_documents_available()
    {
        $response = $this->get('/api/assigned-docs');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'No assigned documents available']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create();

        Doc::factory()->create();
    }

    private function createDummyUserAndDoc()
    {
        $faker = Faker::create();

        $user = User::factory()->create();

        $doc = Doc::factory()->create([
            'status' => 'Basic',
            'title' => $faker->title,
            'deadline' => Carbon::now()->addDays(7),
        ]);
    }
}

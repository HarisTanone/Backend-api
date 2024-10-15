<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }

    public function testIndexReturnsListOfUsers()
    {
        $response = $this->actingAs(User::first(), 'api')
                         ->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['username', 'name', 'created_by', 'created_at', 'updated_by', 'updated_at']
                     ]
                 ]);
    }

    public function testShowReturnsSpecificUser()
    {
        $user = User::first();

        $response = $this->actingAs($user, 'api')
                         ->getJson('/api/users/' . $user->id);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => ['username', 'name', 'created_by', 'created_at', 'updated_by', 'updated_at']
                 ]);
    }

    public function testStoreCreatesNewUser()
    {
        $data = [
            'name' => 'New User',
            'username' => 'newuser',
            'password' => 'password123',
            'confirm_password' => 'password123',
        ];

        $response = $this->actingAs(User::first(), 'api')
                         ->postJson('/api/users', $data);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Username berhasil disimpan']);

        $this->assertDatabaseHas('users', [
            'username' => 'newuser',
            'name' => 'New User'
        ]);
    }

    public function testUpdateModifiesExistingUser()
    {
        $user = User::first();

        $data = [
            'name' => 'Updated Name',
            'username' => 'updatedusername'
        ];

        $response = $this->actingAs($user, 'api')
                         ->putJson('/api/users/' . $user->id, $data);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Username berhasil diperbarui']);

        $this->assertDatabaseHas('users', [
            'username' => 'updatedusername',
            'name' => 'Updated Name'
        ]);
    }

    public function testUpdatePasswordChangesUserPassword()
    {
        $user = User::first();

        $data = [
            'password' => 'newpassword123',
            'confirm_password' => 'newpassword123'
        ];

        $response = $this->actingAs($user, 'api')
                         ->putJson('/api/users/' . $user->id . '/password', $data);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Password berhasil diperbarui']);

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }
}

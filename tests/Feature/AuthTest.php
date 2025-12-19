<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_login_with_valid_credentials()
    {
        User::create([
            'name' => 'Test User',
            'email' => 'test@tots.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@tots.com',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email'],
            ]);
    }

    #[Test]
    public function login_fails_with_invalid_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'wrong@tots.com',
            'password' => 'invalid',
        ]);

        $response->assertStatus(401);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_protected_route()
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(401);
    }

    #[Test]
    public function authenticated_user_can_access_protected_route()
    {
        User::create([
            'name' => 'Authenticated User',
            'email' => 'auth@tots.com',
            'password' => Hash::make('password'),
        ]);

        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'auth@tots.com',
            'password' => 'password',
        ]);

        $token = $loginResponse->json('token');

        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token
        )->getJson('/api/auth/me');

        $response
            ->assertStatus(200)
            ->assertJson([
                'email' => 'auth@tots.com',
            ]);
    }
}

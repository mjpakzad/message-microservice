<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function it_required_to_send_mobile_while_login()
    {
        $user = User::factory()->create();
        $response = $this->postJson(route('auth.login'), ['password' => 'password']);
        $this->assertGuest();
        $response->assertStatus(422)
            ->assertJsonStructure([
                'data' => [
                    0 => [
                        'mobile'
                    ]
                ],
                'server_time'
            ]);
        $response->assertJsonFragment([
            'data' => [
                0 => [
                    'mobile' => [
                        'The mobile field is required.'
                    ],
                ]
            ],
        ]);
    }

    /** @test */
    public function it_required_to_send_password_while_login()
    {
        $user = User::factory()->create();
        $response = $this->postJson(route('auth.login'), ['mobile' => $user->mobile]);
        $this->assertGuest();
        $response->assertStatus(422)
            ->assertJsonStructure([
                'data' => [
                    0 => [
                        'password'
                    ]
                ],
                'server_time'
            ]);
        $response->assertJsonFragment([
            'data' => [
                0 => [
                    'password' => [
                        'The password field is required.'
                    ]
                ]
            ],
        ]);
    }

    /** @test */
    public function it_has_been_authenticated_while_right_credential_and_returns_token()
    {
        $user = User::factory()->create();
        $response = $this->postJson(route('auth.login'), ['mobile' => $user->mobile, 'password' => 'password']);
        $this->assertAuthenticated();
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token'
                ],
                'server_time'
            ]);
    }

    /** @test */
    public function it_does_not_have_authenticated_while_wrong_credetial()
    {
        $user = User::factory()->create();
        $response = $this->postJson(route('auth.login'), ['mobile' => $user->mobile, 'password' => 'wrong-password']);
        $this->assertGuest();
        $response->assertStatus(403)
            ->assertJsonStructure([
                'data' => [
                    'message'
                ],
                'server_time'
            ]);
        $response->assertJsonFragment([
            'data' => [
                'message' =>'Unathenticated!'
            ],
        ]);
    }

    /** @test */
    public function it_can_logout_if_authenticated()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->postJson(route('auth.logout'));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'message'
                ],
                'server_time'
            ]);
        $response->assertJsonFragment([
            'data' => [
                'message' =>'You have been logged out successfully!'
            ],
        ]);
    }

    /** @test */
    public function it_can_not_logout_if_guest()
    {
        $response = $this->postJson(route('auth.logout'));
        $this->assertGuest();
        $response->assertStatus(403)
            ->assertJsonStructure([
                'data' => [
                    'message'
                ],
                'server_time'
            ]);
        $response->assertJsonFragment([
            'data' => [
                'message' =>'Unathenticated!'
            ],
        ]);
    }

    /** @test */
    public function it_can_see_its_data_while_authenticated()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->postJson(route('auth.me'), []);
        $this->assertAuthenticated();
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'first_name',
                    'last_name',
                    'mobile',
                    'mobile_verified_at',
                ],
                'server_time'
            ]);
        $response->assertJsonFragment([
            'data' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'mobile' => $user->mobile,
                'mobile_verified_at' => $user->mobile_verified_at,
            ],
        ]);
    }

    /** @test */
    public function it_cant_see_data_if_guest()
    {
        $response = $this->postJson(route('auth.me'));
        $this->assertGuest();
        $response->assertStatus(403)
            ->assertJsonStructure([
                'data' => [
                    'message'
                ],
                'server_time'
            ]);
        $response->assertJsonFragment([
            'data' => [
                'message' =>'Unathenticated!'
            ],
        ]);
    }
}

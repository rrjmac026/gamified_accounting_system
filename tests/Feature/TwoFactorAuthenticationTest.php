<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_factor_authentication_can_be_enabled()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/user/two-factor-authentication');

        $this->assertNotNull($user->fresh()->two_factor_secret);
        $this->assertCount(8, $user->fresh()->recoveryCodes());
    }

    public function test_two_factor_authentication_can_be_disabled()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/user/two-factor-authentication');
        $this->assertNotNull($user->fresh()->two_factor_secret);

        $this->actingAs($user)->delete('/user/two-factor-authentication');
        $this->assertNull($user->fresh()->two_factor_secret);
    }

    public function test_recovery_codes_can_be_regenerated()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/user/two-factor-authentication');
        $this->assertNotNull($user->fresh()->two_factor_secret);

        $recoveryCodes = $user->fresh()->recoveryCodes();

        $this->actingAs($user)->post('/user/two-factor-recovery-codes');

        $this->assertNotEquals($recoveryCodes, $user->fresh()->recoveryCodes());
    }
}

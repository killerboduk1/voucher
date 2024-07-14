<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoucherControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test getting all vouchers.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = User::factory()->create();
        $vouchers = Voucher::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->get('/api/vouchers');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /**
     * Test storing a new voucher.
     *
     * @return void
     */
    public function testStore()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->post('/api/vouchers', [
            'voucher' => $this->faker->regexify('[A-Za-z0-9]{5}')
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseCount('vouchers', 1);
    }

    /**
     * Test showing a voucher.
     *
     * @return void
     */
    public function testShow()
    {
        $user = User::factory()->create();
        $voucher = Voucher::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->get("/api/vouchers/{$voucher->id}");

        $response->assertStatus(200);
        $response->assertJson(['id' => $voucher->id]);
    }

    /**
     * Test updating a voucher.
     *
     * @return void
     */
    public function testUpdate()
    {
        $user = User::factory()->create();
        $voucher = Voucher::factory()->create(['user_id' => $user->id]);
        $newVoucherCode = $this->faker->regexify('[A-Za-z0-9]{5}');

        $response = $this->actingAs($user, 'sanctum')->put("/api/vouchers/{$voucher->id}", [
            'voucher' => $newVoucherCode
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('vouchers', ['id' => $voucher->id, 'voucher' => $newVoucherCode]);
    }

    /**
     * Test deleting a voucher.
     *
     * @return void
     */
    public function testDestroy()
    {
        $user = User::factory()->create();
        $voucher = Voucher::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->delete("/api/vouchers/{$voucher->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('vouchers', ['id' => $voucher->id]);
    }
}

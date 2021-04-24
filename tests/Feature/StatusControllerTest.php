<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use Database\Seeders\StatusesTableSeeder;

class StatusControllerTest extends TestCase
{
    /**
     *
     * @var ?User
     */
    public $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(StatusesTableSeeder::class);
        $this->user = User::firstOrFail();
    }

    public function testIndex(): void
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        self::assertTrue(isset($this->user));
        if (is_null($this->user)) {
            return;
        }
        $response = $this->get(route('task_statuses.create'));
        $response->assertForbidden();
        // with authentication
        $response = $this->actingAs($this->user)->get(route('task_statuses.create'));
        $response->assertOk();
    }

    public function testEdit(): void
    {
        self::assertTrue(isset($this->user));
        if (is_null($this->user)) {
            return;
        }
        $response = $this->get(route('task_statuses.edit', 1));
        $response->assertForbidden();
        // with authentication
        $response = $this->actingAs($this->user)->get(route('task_statuses.edit', 1));
        $response->assertOk();
    }

    public function testStore(): void
    {
        self::assertTrue(isset($this->user));
        if (is_null($this->user)) {
            return;
        }
        $data = ["name" => "test"];
        $response = $this->post(route('task_statuses.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseMissing('statuses', $data);
        // with authentication
        $response = $this->actingAs($this->user)->post(route('task_statuses.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('statuses', $data);
    }

    public function testUpdate(): void
    {
        $status = Status::firstOrFail();
        self::assertTrue(isset($this->user));
        self::assertTrue(isset($status));
        if (is_null($this->user) || is_null($status)) {
            return;
        }
        $data = ['name' => 'test'];
        $response = $this->patch(route('task_statuses.update', $status->id), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(419);
        $this->assertDatabaseMissing('statuses', $data);
        // with authentication
        $response = $this->actingAs($this->user)->patch(route('task_statuses.update', $status), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('statuses', $data);
    }

    public function testDestroy(): void
    {
        $status = Status::firstOrFail();
        self::assertTrue(isset($this->user));
        self::assertTrue(isset($status));
        if (is_null($this->user) || is_null($status)) {
            return;
        }
        $data = ["name" => $status->name, 'id' => $status->id];
        $response = $this->delete(route('task_statuses.destroy', $status->id));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(419);
        $this->assertDatabaseHas('statuses', $data);
        // with authentication
        $response = $this->actingAs($this->user)->delete(route('task_statuses.destroy', $status));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('statuses', $status->toArray());
    }
}

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
     * @var User
     */
    public $user;

    /**
     *
     * @var Status
     */
    public $status;

    /**
     *
     * @var array
     */
    public $data;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->status = Status::factory()->create(['user_id' => $this->user->id]);
        $this->data = $this->status->only(['id', 'name', "user_id"]);
    }

    public function testIndex(): void
    {
        Status::factory()->count(10)->create();
        $response = $this->get(route('task_statuses.index'));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->get(route('task_statuses.create'));
        $response->assertForbidden();
        // with authentication
        $response = $this->actingAs($this->user)->get(route('task_statuses.create'));
        $response->assertOk();
    }

    public function testEdit(): void
    {
        $response = $this->get(route('task_statuses.edit', $this->status));
        $response->assertForbidden();
        // with authentication
        $response = $this->actingAs($this->user)->get(route('task_statuses.edit', $this->status));
        $response->assertOk();
    }

    public function testStore(): void
    {
        $data = Status::factory()->make(['user_id' => $this->user->id])->toArray();
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
        $newData = array_merge($this->data, ['name' => 'test']);
        $response = $this->patch(route('task_statuses.update', $this->status), $newData);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(419);
        $this->assertDatabaseMissing('statuses', $newData);
        // with authentication
        $response = $this->actingAs($this->user)->patch(route('task_statuses.update', $this->status), $newData);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('statuses', $newData);
    }

    public function testDestroy(): void
    {
        $response = $this->delete(route('task_statuses.destroy', $this->status));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(419);
        $this->assertDatabaseHas('statuses', $this->data);
        // with authentication
        $response = $this->actingAs($this->user)->delete(route('task_statuses.destroy', $this->status));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('statuses', $this->data);
    }
}

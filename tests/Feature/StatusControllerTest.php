<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use Database\Seeders\StatusesTableSeeder;

class StatusControllerTest extends TestCase
{
    public $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(StatusesTableSeeder::class);
        $this->user = User::first();
    }

    public function testIndex()
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertOk();
    }

    public function testCreate()
    {
        $response = $this->get(route('task_statuses.create'));
        $response->assertForbidden();
    }

    public function testEdit()
    {
        $response = $this->get(route('task_statuses.edit', 1));
        $response->assertForbidden();
    }

    public function testStore()
    {
        $data = ["name" => "test"];
        $response = $this->post(route('task_statuses.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(403);
    }

    public function testUpdate()
    {
        $status = Status::first();
        $statusNew = Status::first();
        $statusNew->name = 'test';
        $data = $statusNew->toArray();
        $response = $this->patch(route('task_statuses.update', $status->id), $data);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('statuses', $data);
    }

    public function testDestroy()
    {
        $status = Status::first();
        $data = ["name" => $status->name, 'id' => $status->id];
        $response = $this->delete(route('task_statuses.destroy', $status->id));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('statuses', $data);
    }

    public function testCreateWithAuthentication()
    {
        $response = $this->actingAs($this->user)->get(route('task_statuses.create'));
        $response->assertOk();
    }

    public function testEditWithAuthentication()
    {
        $response = $this->actingAs($this->user)->get(route('task_statuses.edit', 1));
        $response->assertOk();
    }

    public function testStoreWithAuthentication()
    {
        $data = ["name" => "test"];
        $response = $this->actingAs($this->user)->post(route('task_statuses.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('statuses', $data);
    }

    public function testUpdateWithAuthentication()
    {
        $status = Status::first();
        $data = ['name' => 'test'];
        $response = $this->actingAs($this->user)->patch(route('task_statuses.update', $status), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('statuses', $data);
    }

    public function testDestroyWithAuthentication()
    {
        $status = Status::first();
        $response = $this->actingAs($this->user)->delete(route('task_statuses.destroy', $status));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('statuses', $status->toArray());
    }
}

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
        $response = $this->get(route('statuses.index'));
        $response->assertOk();
    }

    public function testCreate()
    {
        $response = $this->get(route('statuses.create'));
        $response->assertForbidden();
    }

    public function testEdit()
    {
        $response = $this->get(route('statuses.edit', 1));
        $response->assertForbidden();
    }

    public function testStore()
    {
        $data = ["name" => "test"];
        $response = $this->post(route('statuses.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(419);
    }

    public function testUpdate()
    {
        $status = Status::first();
        $data = ['name' => 'test'];
        $response = $this->patch(route('statuses.update', $status), $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(419);
        $response->assertRedirect();
        $this->assertDatabaseMissing('statuses', $data);
    }

    public function testDestroy()
    {
        $status = Status::first();
        $response = $this->delete(route('statuses.destroy', $status));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(419);
        $response->assertRedirect();
        $this->assertDatabaseHas('statuses', $status->toArray());
    }

    public function testCreateWithAuthentication()
    {
        $response = $this->actingAs($this->user)->get(route('statuses.create'));
        $response->assertOk();
    }

    public function testEditWithAuthentication()
    {
        $response = $this->actingAs($this->user)->get(route('statuses.edit', 1));
        $response->assertOk();
    }

    public function testStoreWithAuthentication()
    {
        $data = ["name" => "test"];
        $newId = 5;
        $response = $this->actingAs($this->user)->post(route('statuses.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('statuses', $data);
        $response = $this->get(route('statuses.show', $newId));
        $response->assertSeeText($data['name']);
    }

    public function testUpdateWithAuthentication()
    {
        $status = Status::first();
        $data = ['name' => 'test'];
        $response = $this->actingAs($this->user)->patch(route('statuses.update', $status), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('statuses', $data);
    }

    public function testDestroyWithAuthentication()
    {
        $status = Status::first();
        $response = $this->actingAs($this->user)->delete(route('statuses.destroy', $status));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('statuses', $status->toArray());
    }
}

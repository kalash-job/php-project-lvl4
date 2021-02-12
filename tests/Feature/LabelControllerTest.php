<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\LabelsTableSeeder;
use Database\Seeders\StatusesTableSeeder;
use Database\Seeders\TasksTableSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelControllerTest extends TestCase
{
    public $user;
    public $label;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(StatusesTableSeeder::class);
        $this->seed(LabelsTableSeeder::class);
        $this->seed(TasksTableSeeder::class);
        $this->user = User::first();
        $this->label = Label::first();
    }

    public function testIndex()
    {
        $response = $this->get(route('labels.index'));
        $response->assertOk();
    }

    public function testShow()
    {
        $response = $this->get(route('labels.show', $this->label));
        $response->assertOk();
    }

    public function testCreate()
    {
        $response = $this->get(route('labels.create'));
        $response->assertForbidden();
    }

    public function testEdit()
    {
        $response = $this->get(route('labels.edit', 1));
        $response->assertForbidden();
    }

    public function testStore()
    {
        $data = ["name" => "testing"];
        $response = $this->post(route('labels.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseMissing('labels', $data);
    }

    public function testUpdate()
    {
        $this->label->name = 'testing';
        $data = $this->label->toArray();
        $response = $this->patch(route('labels.update', $this->label), $data);
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseMissing('labels', $data);
    }

    public function testDestroy()
    {
        $data = ["name" => $this->label->name, 'id' => $this->label->id];
        $response = $this->delete(route('labels.destroy', $this->label));
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseHas('labels', $data);
    }

    public function testCreateWithAuthentication()
    {
        $response = $this->actingAs($this->user)->get(route('labels.create'));
        $response->assertOk();
    }

    public function testEditWithAuthentication()
    {
        $response = $this->actingAs($this->user)->get(route('labels.edit', 1));
        $response->assertOk();
    }

    public function testStoreWithAuthentication()
    {
        $data = ["name" => "testing"];
        $response = $this->actingAs($this->user)->post(route('labels.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', $data);
    }

    public function testUpdateWithAuthentication()
    {
        $this->label->name = 'testing';
        $data = $this->label->only("name", "description");
        $response = $this->actingAs($this->user)->patch(route('labels.update', $this->label), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', $data);
    }

    public function testDestroyWithAuthentication()
    {
        $data = ['id' => $this->label->id];
        $response = $this->actingAs($this->user)->delete(route('labels.destroy', $this->label->id));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('labels', $data);
    }
}

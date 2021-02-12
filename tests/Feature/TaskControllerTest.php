<?php

namespace Tests\Feature;

use App\Models\{User, Task};
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\{StatusesTableSeeder, TasksTableSeeder};

class TaskControllerTest extends TestCase
{
    public $user;
    public $task;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(StatusesTableSeeder::class);
        $this->seed(TasksTableSeeder::class);
        $this->user = User::find(2);
        $this->task = Task::first();
    }

    public function testIndex()
    {
        $response = $this->get(route('tasks.index'));
        $response->assertOk();
    }

    public function testShow()
    {
        $response = $this->get(route('tasks.show', $this->task));
        $response->assertOk();
    }

    public function testCreate()
    {
        $response = $this->get(route('tasks.create'));
        $response->assertForbidden();
    }

    public function testEdit()
    {
        $response = $this->get(route('tasks.edit', 1));
        $response->assertForbidden();
    }

    public function testStore()
    {
        $data = ["name" => "testing", 'created_by_id' => 1, 'assigned_to_id' => 2, 'status_id' => 1];
        $response = $this->post(route('tasks.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseMissing('tasks', $data);
    }

    public function testUpdate()
    {
        $this->task->name = 'testing';
        $data = $this->task->toArray();
        $response = $this->patch(route('tasks.update', $this->task), $data);
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseMissing('tasks', $data);
    }

    public function testDestroy()
    {
        $data = ["name" => $this->task->name, 'id' => $this->task->id];
        $response = $this->delete(route('tasks.destroy', $this->task->id));
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testCreateWithAuthentication()
    {
        $response = $this->actingAs($this->user)->get(route('tasks.create'));
        $response->assertOk();
    }

    public function testEditWithAuthentication()
    {
        $response = $this->actingAs($this->user)->get(route('tasks.edit', 1));
        $response->assertOk();
    }

    public function testStoreWithAuthentication()
    {
        $data = ["name" => "testing", 'created_by_id' => 2, 'assigned_to_id' => 1, 'status_id' => 1];
        $response = $this->actingAs($this->user)->post(route('tasks.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testUpdateWithAuthentication()
    {
        $this->task->name = 'testing';
        $data = $this->task->only("name", "description", "status_id", "assigned_to_id", "created_by_id");
        $response = $this->actingAs($this->user)->patch(route('tasks.update', $this->task), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testDestroyWithAuthentication()
    {
        $task = Task::find(3);
        $data = ['id' => $task->id];
        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', $task->id));
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testDestroyByCreator()
    {
        $data = ['id' => $this->task->id];
        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', $this->task));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', $data);
    }
}

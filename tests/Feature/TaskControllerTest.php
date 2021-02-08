<?php

namespace Tests\Feature;

use App\Models\{User, Task};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\{StatusesTableSeeder, TasksTableSeeder};

class TaskControllerTest extends TestCase
{
    public $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(StatusesTableSeeder::class);
        $this->seed(TasksTableSeeder::class);
        $this->user = User::first();
    }

    public function testIndex()
    {
        $response = $this->get(route('tasks.index'));
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
        $data = ["name" => "testing", 'created_by_id' => 1, 'assigned_to_id' => 2];
        $response = $this->post(route('tasks.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
    }

    public function testUpdate()
    {
        $task = Task::first();
        $taskNew = Task::first();
        $taskNew->name = 'testing';
        $data = $taskNew->toArray();
        $response = $this->patch(route('tasks.update', $task->id), $data);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('tasks', $data);
    }

    public function testDestroy()
    {
        $task = Task::first();
        $data = ["name" => $task->name, 'id' => $task->id];
        $response = $this->delete(route('tasks.destroy', $task->id));
        $response->assertSessionHasNoErrors();
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
        $data = ["name" => "testing", 'created_by_id' => 1, 'assigned_to_id' => 2];
        $response = $this->actingAs($this->user)->post(route('tasks.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testUpdateWithAuthentication()
    {
        $task = Task::first();
        $taskNew = Task::first();
        $taskNew->name = 'testing';
        $data = $taskNew->toArray();
        $response = $this->actingAs($this->user)->patch(route('tasks.update', $task), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testDestroyWithAuthentication()
    {
        $task = Task::find(3);
        $data = ["name" => $task->name, 'id' => $task->id];
        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', $task->id));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testDestroyByCreator()
    {
        $task = Task::first();
        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', $task));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', $task->toArray());
    }
}

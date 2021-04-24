<?php

namespace Tests\Feature;

use App\Models\{User, Task};
use Tests\TestCase;
use Database\Seeders\{StatusesTableSeeder, TasksTableSeeder};

class TaskControllerTest extends TestCase
{
    /**
     *
     * @var ?User
     */
    public $user;

    /**
     *
     * @var ?Task
     */
    public $task;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(StatusesTableSeeder::class);
        $this->seed(TasksTableSeeder::class);
        $this->user = User::findOrFail(2);
        $this->task = Task::firstOrFail();
    }

    public function testIndex(): void
    {
        $response = $this->get(route('tasks.index'));
        $response->assertOk();
    }

    public function testShow(): void
    {
        self::assertTrue(isset($this->task));
        if (is_null($this->task)) {
            return;
        }
        $response = $this->get(route('tasks.show', $this->task));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        self::assertTrue(isset($this->user));
        if (is_null($this->user)) {
            return;
        }
        $response = $this->get(route('tasks.create'));
        $response->assertForbidden();
        // with authentication
        $response = $this->actingAs($this->user)->get(route('tasks.create'));
        $response->assertOk();
    }

    public function testEdit(): void
    {
        self::assertTrue(isset($this->user));
        if (is_null($this->user)) {
            return;
        }
        $response = $this->get(route('tasks.edit', 1));
        $response->assertForbidden();
        // with authentication
        $response = $this->actingAs($this->user)->get(route('tasks.edit', 1));
        $response->assertOk();
    }

    public function testStore(): void
    {
        self::assertTrue(isset($this->user));
        if (is_null($this->user)) {
            return;
        }
        $data = ["name" => "testing", 'created_by_id' => 2, 'assigned_to_id' => 1, 'status_id' => 1];
        $response = $this->post(route('tasks.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseMissing('tasks', $data);
        // with authentication
        $response = $this->actingAs($this->user)->post(route('tasks.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testUpdate(): void
    {
        self::assertTrue(isset($this->user));
        self::assertTrue(isset($this->task));
        if (is_null($this->user) || is_null($this->task)) {
            return;
        }
        $this->task->name = 'testing';
        $data = $this->task->only("name", "description", "status_id", "assigned_to_id", "created_by_id");
        $response = $this->patch(route('tasks.update', $this->task), $data);
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseMissing('tasks', $data);
        // with authentication
        $response = $this->actingAs($this->user)->patch(route('tasks.update', $this->task), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testDestroy(): void
    {
        self::assertTrue(isset($this->user));
        self::assertTrue(isset($this->task));
        if (is_null($this->user) || is_null($this->task)) {
            return;
        }
        $data = ['id' => $this->task->id];
        $response = $this->delete(route('tasks.destroy', $this->task->id));
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', $data);
        // with authorization (user is not creator)
        $task = Task::findOrFail(3);
        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', $task->id));
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', $data);
        // with authentication (user is creator)
        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', $this->task));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', $data);
    }
}

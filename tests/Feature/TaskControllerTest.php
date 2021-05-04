<?php

namespace Tests\Feature;

use App\Models\{User, Task, Label};
use Tests\TestCase;
use Database\Seeders\{StatusesTableSeeder, TasksTableSeeder};

class TaskControllerTest extends TestCase
{
    /**
     *
     * @var User
     */
    public $user;

    /**
     *
     * @var Task
     */
    public $task;

    /**
     *
     * @var array
     */
    public $data;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->task = Task::factory()->create(['created_by_id' => $this->user->id]);
        $this->data = $this->task->only(['id', 'name', "description", "status_id", "assigned_to_id"]);
        $this->task->labels()->sync([0 => Label::factory()->create()->id]);
    }

    public function testIndex(): void
    {
        Task::factory()->count(20)->create()->each(function ($task): void {
            $task->labels()->sync([0 => Label::factory()->create()->id]);
        });
        $response = $this->get(route('tasks.index'));
        $response->assertOk();
    }

    public function testShow(): void
    {
        $response = $this->get(route('tasks.show', $this->task));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->get(route('tasks.create'));
        $response->assertForbidden();
        // with authentication
        $response = $this->actingAs($this->user)->get(route('tasks.create'));
        $response->assertOk();
    }

    public function testEdit(): void
    {
        $response = $this->get(route('tasks.edit', $this->task));
        $response->assertForbidden();
        // with authentication
        $response = $this->actingAs($this->user)->get(route('tasks.edit', $this->task));
        $response->assertOk();
    }

    public function testStore(): void
    {
        $data = Task::factory()->make(['created_by_id' => $this->user->id])->toArray();

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
        $newData = array_merge($this->data, ['name' => 'testing']);
        $response = $this->patch(route('tasks.update', $this->task), $newData);
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseMissing('tasks', $newData);
        // with authentication
        $response = $this->actingAs($this->user)->patch(route('tasks.update', $this->task), $newData);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', $newData);
    }

    public function testDestroy(): void
    {
        $response = $this->delete(route('tasks.destroy', $this->task->id));
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', $this->data);
        // with authorization (user is not creator)
        $task = Task::factory()->create();
        $data = $task->only(['id', 'name']);

        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', $task->id));
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', $data);

        // with authentication (user is creator)
        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', $this->task));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', $this->data);
    }
}

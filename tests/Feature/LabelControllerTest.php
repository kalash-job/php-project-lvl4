<?php

namespace Tests\Feature;

use App\Models\{User, Label};
use Database\Seeders\LabelsTableSeeder;
use Database\Seeders\StatusesTableSeeder;
use Database\Seeders\TasksTableSeeder;
use Tests\TestCase;

class LabelControllerTest extends TestCase
{
    /**
     *
     * @var ?User
     */
    public $user;
    /**
     *
     * @var ?Label
     */
    public $label;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(StatusesTableSeeder::class);
        $this->seed(LabelsTableSeeder::class);
        $this->seed(TasksTableSeeder::class);
        $this->user = User::firstOrFail();
        $this->label = Label::firstOrFail();
    }

    public function testIndex(): void
    {
        $response = $this->get(route('labels.index'));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->get(route('labels.create'));
        $response->assertForbidden();
        self::assertTrue(isset($this->user));
        $response = $this->actingAs($this->user)->get(route('labels.create'));
        $response->assertOk();
    }

    public function testEdit(): void
    {
        self::assertTrue(isset($this->user));
        if (is_null($this->user)) {
            return;
        }
        $response = $this->get(route('labels.edit', 1));
        $response->assertForbidden();
        // with authentication
        $response = $this->actingAs($this->user)->get(route('labels.edit', 1));
        $response->assertOk();
    }

    public function testStore(): void
    {
        self::assertTrue(isset($this->user));
        if (is_null($this->user)) {
            return;
        }
        $data = ["name" => "testing"];
        $response = $this->post(route('labels.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseMissing('labels', $data);
        // with authentication
        $response = $this->actingAs($this->user)->post(route('labels.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', $data);
    }

    public function testUpdate(): void
    {
        self::assertTrue(isset($this->label));
        self::assertTrue(isset($this->user));
        if (is_null($this->label) || is_null($this->user)) {
            return;
        }
        $this->label->name = 'testing';
        $data = $this->label->only("name", "description");
        $response = $this->patch(route('labels.update', $this->label), $data);
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseMissing('labels', $data);
        // with authentication
        $response = $this->actingAs($this->user)->patch(route('labels.update', $this->label), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', $data);
    }

    public function testDestroy(): void
    {
        self::assertTrue(isset($this->label));
        self::assertTrue(isset($this->user));
        if (is_null($this->label) || is_null($this->user)) {
            return;
        }
        $data = ['id' => $this->label->id];
        $response = $this->delete(route('labels.destroy', $this->label));
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseHas('labels', $data);
        // with authentication
        $response = $this->actingAs($this->user)->delete(route('labels.destroy', $this->label->id));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('labels', $data);
    }
}

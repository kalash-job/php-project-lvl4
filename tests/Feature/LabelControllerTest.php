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
     * @var User
     */
    public $user;
    /**
     *
     * @var Label
     */
    public $label;

    /**
     *
     * @var array
     */
    public $data;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->label = Label::factory()->create(['user_id' => $this->user->id]);
        $this->data = $this->label->only(['id', 'name', "user_id", 'description']);
    }

    public function testIndex(): void
    {
        $this->label = Label::factory()->count(10)->create();
        $response = $this->get(route('labels.index'));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->get(route('labels.create'));
        $response->assertForbidden();
        $response = $this->actingAs($this->user)->get(route('labels.create'));
        $response->assertOk();
    }

    public function testEdit(): void
    {
        $response = $this->get(route('labels.edit', $this->label));
        $response->assertForbidden();
        // with authentication
        $response = $this->actingAs($this->user)->get(route('labels.edit', $this->label));
        $response->assertOk();
    }

    public function testStore(): void
    {
        $data = Label::factory()->make(['user_id' => $this->user->id])->toArray();
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
        $newData = array_merge($this->data, ['name' => 'testing']);
        $response = $this->patch(route('labels.update', $this->label), $newData);
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseMissing('labels', $newData);
        // with authentication
        $response = $this->actingAs($this->user)->patch(route('labels.update', $this->label), $newData);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('labels', $newData);
    }

    public function testDestroy(): void
    {
        $response = $this->delete(route('labels.destroy', $this->label));
        $response->assertSessionHasNoErrors();
        $response->assertForbidden();
        $this->assertDatabaseHas('labels', $this->data);
        // with authentication
        $response = $this->actingAs($this->user)->delete(route('labels.destroy', $this->label));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('labels', $this->data);
    }
}

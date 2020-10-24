<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guess_cannot_add_task_to_a_project()
    {
        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks')->assertRedirect('login');

    }

    /** @test */
    public function only_owner_of_a_project_can_add_task()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task.'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task.']);
    }

    /** @test */
    public function only_owner_of_a_project_can_update_tasks()
    {
        $this->signIn();

        $project = ProjectFactory::withTask(1)->create();

        $this->patch($project->tasks->first()->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }


    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->post($project->path() . '/tasks', ['body' => 'Test Task']);

        $this->get($project->path())->assertSee('Test Task');
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $project = ProjectFactory::withTask(1)->create();

        $attributes = ['body' => 'changed'];

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /** @test */
    public function a_task_can_be_completed()
    {
        $project = ProjectFactory::withTask(1)->create();

        $attributes = ['body' => 'changed', 'completed' => now()->toDateTimeString()];

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /** @test */
    public function a_task_can_be_marked_as_incompleted()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::withTask(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), ['body' => 'changed', 'completed' => now()->toDateTimeString()]);

        $this->patch($project->tasks[0]->path(), ['body' => 'changed', 'completed' => null]);

        $this->assertDatabaseHas('tasks', ['body' => 'changed', 'completed' => null]);
    }

    /** @test */
    public function a_task_require_a_body()
    {
        $project = ProjectFactory::create();
        $attributes = factory('App\Task')->raw(['body' => '']);
        $this->actingAs($project->owner)->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors('body');
    }


}

<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_guest_cannot_manage_projects()
    {
        // $this->withoutExceptionHandling();
        $project = ProjectFactory::create();

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path() . '/edit')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }


    /** @test */
    public function guests_cannot_create_project()
    {
        $attributes = factory(Project::class)->raw();
        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function guests_cannot_view_projects()
    {
        $this->get('/projects')->assertRedirect('/login');
    }

    /** @test */
    public function guests_cannot_view_a_single_project()
    {
        // $this->withoutExceptionHandling();
        $project = factory(Project::class)->create();

        $this->get($project->path())->assertRedirect('/login');

    }


    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->signIn();

        $attributes = [
            'title' => $this->faker->word,
            'description' => $this->faker->sentence(4),
            'notes' => 'general notes here..'
        ];

        $response = $this->post('/projects', $attributes);
        $project = Project::where($attributes)->first();
        $response->assertRedirect($project->path());

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        // $this->withoutExceptionHandling();
        $project = ProjectFactory::create();

        $attributes = [
            'title' => 'changed',
            'description' => 'changed description',
            'notes' => 'changed notes'
        ];

        $this->actingAs($project->owner)->patch($project->path(), $attributes)->assertRedirect($project->path());

        $this->get($project->path(). '/edit')->assertOk();

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_update_projects_general_notes()
    {
        $project = ProjectFactory::create();

        $attributes = [
            'notes' => 'changed notes'
        ];

        $this->get($project->path(). '/edit')->assertRedirect('login');
        $this->actingAs($project->owner)->patch($project->path(), $attributes);
        $this->assertDatabaseHas('projects', $attributes);
    }


    /** @test */
    public function a_user_can_view_their_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function authenticated_user_cannot_view_the_project_of_others()
    {
        $this->signIn();

        $project = ProjectFactory::create();

        $this->get($project->path())
            ->assertStatus(403);
    }

    /** @test */
    public function authenticated_user_cannot_update_the_project_of_others()
    {
        $this->signIn();

        $project = ProjectFactory::create();

        $this->patch($project->path())->assertStatus(403);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();
        $attributes = factory(Project::class)->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = factory(Project::class)->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}

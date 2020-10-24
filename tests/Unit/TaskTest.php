<?php

namespace Tests\Unit;

use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_project()
    {
        $task = factory('App\Task')->create();

        $this->assertInstanceOf('App\Project', $task->project);
    }

    /** @test */
    public function it_has_a_path()
    {
        $this->withoutExceptionHandling();

        $task = factory('App\Task')->create();

        $this->assertEquals('/projects/' . $task->project->id . '/tasks/' . $task->id, $task->path());
    }

    /** @test */
    public function it_can_be_completed()
    {
        $task = factory('App\Task')->create();

        $this->assertNull($task->fresh()->completed);

        $task->complete();

        $this->assertNotNull($task->fresh()->completed);
    }

    /** @test */
    public function it_can_be_mark_as_incompleted()
    {
        $task = factory('App\Task')->create();
        $task->complete();
        $this->assertNotNull($task->fresh()->completed);
        $task->incomplete();
        $this->assertNull($task->fresh()->completed);
    }

}

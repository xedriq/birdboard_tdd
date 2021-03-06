<?php

namespace Tests\Setup;

use App\Project;
use App\Task;
use App\User;

class ProjectFactory
{
    protected $tasksCount = 0;

    public function withTask($count)
    {
        $this->tasksCount = $count;

        return $this;
    }
    public function create()
    {
        $project = factory(Project::class)->create([
            'owner_id' => factory(User::class)
        ]);

        factory(Task::class, $this->tasksCount)->create([
            'project_id' => $project->id
        ]);

        return $project;
    }
}

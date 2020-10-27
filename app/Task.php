<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $touches = ['project'];

    public function complete()
    {
        $this->update(['completed' => now()->toDateTimeString()]);

        $this->project->recordActivity('completed_task');

    }

    public function incomplete()
    {
        $this->update(['completed' => null]);

        $this->project->recordActivity('incompleted_task');

    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function path()
    {
        return '/projects/' . $this->project->id . '/tasks/' . $this->id;
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject')->latest();
    }

    public function recordActivity($description)
    {
        $this->activity()->create([
            'project_id' => $this->project_id,
            'description' => $description
        ]);
    }
}

@extends('layouts.app')

@section('content')

<header class="flex items-center mb-3 py-4">
    <div class="flex justify-between items-end w-full">
        <p class="text-gray-500"><a href="{{ '/projects' }}">My Projects</a> / {{ $project->title }}</p>
        <a href="{{ $project->path() . '/edit' }}" class="text-gray-500 button">Edit Project</a>
    </div>
</header>

<main>
    <div class="lg:flex -mx-3">
        <div class="lg:w-3/4 px-3">
            <div class="mb-8">
                <h2 class="text-gray-500 font-normal text-lg mb-3">Tasks</h2>
                {{-- tasks --}}
                @foreach ($project->tasks as $task)
                    <div class="card mb-3">
                        <form action="{{ $task->path() }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="flex items-center">
                                <input class="w-full {{ $task->completed ? 'text-gray-400' : '' }}" type="text" name="body" value="{{ $task->body }}">
                                <input type="checkbox" name="completed" onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
                            </div>
                        </form>
                    </div>
                @endforeach
                <form action="{{ $project->path() . '/tasks' }}" method="POST">
                    @csrf
                    <input name="body" type="text" class="card w-full" placeholder="Add new task...">
                </form>

            </div>
            <div class="">
                <h2 class="text-gray-500 font-normal text-lg mb-3">General Notes</h2>
                <form action="{{ $project->path() }}" method="post">
                    @method('PATCH')
                    @csrf
                    <textarea name="notes" class="card w-full mb-3" style="min-height: 200px;" placeholder="Add notes here...">{{ $project->notes }}</textarea>
                    <button class="button" type="submit">Save</button>
                </form>
            </div>
        </div>
        <div class="lg:w-1/4 px-3">
                @include('projects.partials._card')
                <div class="card mt-3">
                    <ul class="text-xs">
                        @foreach ($project->activity as $activity)
                            <li class="{{ $loop->last ?: 'mb-1' }}">{{ $activity->description }}</li>
                        @endforeach
                    </ul>
                </div>
                {{-- <h1>{{ $project->title }}</h1>
                <p>{{ $project->description }}</p>
                <a href="/projects">Go Back</a> --}}
        </div>
    </div>
</main>


@endsection

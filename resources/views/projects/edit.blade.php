@extends('layouts.app')

@section('content')
    <div class="lg:w-1/2 lg:mx-auto bg-white p-6 md:py12 md:px-16 rounded shadow">
        <h1 class="text-2xl font-normal mb-10 text-center">Edit Project</h1>

        <form action="{{ $project->path() }}" method="post">
            @method('PATCH')
            @include('projects.partials._form',['btnString' => 'Update Project'])
        </form>
    </div>

@endsection

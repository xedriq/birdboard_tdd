@extends('layouts.app')

@section('content')
    <div class="lg:w-1/2 lg:mx-auto bg-white p-6 md:py12 md:px-16 rounded shadow">
        <h1 class="text-2xl font-normal mb-10 text-center">Let's start something new...</h1>

        <form action="{{ '/projects' }}" method="post">
            @include('projects.partials._form', ['project' => new App\Project, 'btnString' => 'Create Project'])
        </form>
    </div>
@endsection

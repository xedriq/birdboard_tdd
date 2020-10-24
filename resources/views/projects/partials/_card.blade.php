<div class="card" style="height: 250px;">
    <h3 class="font-normal text-xl py-4 -ml-5 border-l-4 border-blue-regular-light pl-4 mb-3">
        <a href="{{ $project->path() }}">{{ $project->title }}</a>
    </h3>
    <div class="text-gray-500">{{ str_limit($project->description, 100) }}</div>
</div>

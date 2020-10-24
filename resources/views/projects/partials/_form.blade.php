@csrf

<div class="field mb-6">
    <label for="title" class="label text-sm mb-2 block">Title</label>
    <div class="control">
        <input type="text" class="input bg-transparent border border-gray-light rounded p-2 text-xs w-full" name="title" placeholder="Add title" value="{{ $project->title }}" required>
    </div>
</div>

<div class="field">
    <label for="description" class="label text-sm mb-2 block">Description</label>
    <div class="control">
        <textarea name="description" rows="10" placeholder="Add desciption" class="textarea bg-transparent border border-gray-light rounded p-2 text-xs w-full mb-4" required>{{ $project->description }}</textarea>
    </div>
</div>

<div class="field">
    <div class="control">
        <button class="button is-link mr-2" type="submit">{{ $btnString }}</button>
        <a href="{{ $project->path() }}">Cancel</a>
    </div>
</div>

@if ($errors->any())
    <div class="field mt-6">
        @foreach ($errors->all() as $error)
            <li class="text-sm text-red-500">
                {{ $error }}
            </li>
        @endforeach
    </div>
@endif

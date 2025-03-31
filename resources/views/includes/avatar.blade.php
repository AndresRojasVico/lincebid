@if(Auth::user()->image)
<div class="mt-4">
    <x-input-label for="image_path" :value="__('Avatar')" />
    <img src="{{ route('user.image', ['filename' => Auth::user()->image]) }}" class="rounded-full h-20 w-20  mx-auto" />
</div>
@endif
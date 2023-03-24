<x-app-layout>
  <x-slot:header>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ isset($article) ? __('Edit Article') : __('Create Article') }}
    </h2>
  </x-slot>

  <div class="md:flex md:items-center py-2">
    <div class="md:w-1/2 px-10">
      <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data" id="dropzonenpm" class="dropzone border-dashed border-2 w-full h-96 rounded flex flex-col justify-center items-center">
        @csrf

        <span class="text">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
          </svg>
        </span>
      </form>
    </div>

    <div class="md:w-1/2 p-10 bg-white rounded-lg shadow-xl mt-10 md:mt-0">
      <form action="{{ route('articles.store') }}" method="POST" novalidate>
        @csrf

        <div class="mb-5">
          <x-input-label for="name" :value="__('Name')" />
          <x-text-input type="text" id="name" name="name" class="block border p-3 w-full rounded-lg @error('name') border-red-500 @enderror" :value="$article->name ?? old('name')" autofocus autocomplete="name" />
          @error('name')
            <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">
              {{ $message }}
            </p>
          @enderror
        </div>

        <div class="mb-5">
          <x-input-label for="description" :value="__('Description')" />
          <textarea id="description" name="description"
            class="border p-3 w-full rounded-lg  @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
          @error('description')
            <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">
              {{ $message }}
            </p>
          @enderror
        </div>

        <div class="mb-5">
          <input name="image" type="hidden" class="border-dashed border-2 border-indigo-600 p-3 w-full rounded-lg bg-red-50" value="{{ old('image') }}" />
          @error('image')
            <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">
              {{ $message }}
            </p>
          @enderror
        </div>

        <input type="submit" value="{{ __('Create') }}"
          class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg" />
      </form>
    </div>
  </div>

  @push('styles')
    <style>
      .dropzone {
        background: white !important;
        border: 2px dashed #3498db !important;
        border-radius: 5px;
        color: #050505;
        margin: 1%;
        padding: 5em;
        transition: .2s;
        -webkit-transition: .2s;
      }
      .dz-image img {
        width: 100%;
        height: 100%;
      }
      .dropzone.dz-started .dz-message {
        display: block !important;
        -webkit-transition: .2s;
        transition: .2s;
      }
      .dropzone .dz-preview.dz-complete .dz-success-mark {
          opacity: 1;
      }
      .dropzone .dz-preview.dz-error .dz-success-mark {
          opacity: 0;
      }
      .dropzone .dz-preview .dz-error-message{
        top: 144px;
      }
      
      .dropzone.dz-clickable { cursor: pointer; }
      .dropzone.dz-clickable * { cursor: default; }
      .dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message * {
        cursor: pointer; 
      }
      .dropzone.dz-drag-hover {
        border-style: solid;
      }
      .dropzone.dz-drag-hover .dz-message { opacity: 0.5; }
      .dropzone .dz-message {
        color: #666;
        font-size: 1em;
        margin: 0.2em 0;
        text-align: center;
      }
      .dropzone-previews { display: none !important; }
    </style>
  @endpush
</x-app-layout>
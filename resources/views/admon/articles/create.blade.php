<x-app-layout>
  <x-slot:header>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ isset($article) ? __('Edit Article') : __('Create Article') }}
    </h2>
  </x-slot>

  <div class="md:flex md:items-center py-2">
    <div class="md:w-1/2 px-10">
      {{-- <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data" id="dropzonenpm"
        class="dropzone flex flex-col justify-center items-center w-full h-96 rounded border-dashed border-2">
        @csrf
      </form> --}}
      <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data" id="dropzonenpm" class="dropzone border-dashed border-2 w-full h-96 rounded flex flex-col justify-center items-center">
        @csrf
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
        border: 2px dashed #028AF4 !important;
        border-radius: 5px 5px 5px 5px;
        padding: 5px;
      }
      .dz-image img {
        width: 100%;
        height: 100%;
      }
      .dropzone.dz-started .dz-message {
        display: block !important;
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

      /* .dropzone {
        border: 2px dashed #428BCA;
        border-radius: 5px 5px 5px 5px;
        padding: 5px;
      } */
      .dropzone.dz-clickable { cursor: pointer; }
      .dropzone.dz-clickable * { cursor: default; }
      .dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message * { cursor: pointer; }
      /* .dropzone.dz-started .dz-message { display: none; } */
      .dropzone.dz-drag-hover { border-style: solid; }
      .dropzone.dz-drag-hover .dz-message { opacity: 0.5; }
      .dropzone .dz-message {
        color: #666;
        font-size: 1.2em;
        margin: 0.2em 0;
        text-align: center;
      }
      .dropzone-previews { display: none !important; }
    </style>
  @endpush
</x-app-layout>
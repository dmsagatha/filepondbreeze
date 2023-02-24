<x-guest-layout>
  <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div>
      <x-input-label for="title" :value="__('Título')" />
      <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus autocomplete="title" />
      <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <!-- Photo -->
    <div class="mt-4">
      <x-input-label for="photo" :value="__('Fotografía')" />
      
      <input type="file" name="photo" id="photo">

      <x-input-error :messages="$errors->get('photo')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">
      <x-primary-button class="ml-4">
        {{ __('Guardar Datos') }}
      </x-primary-button>
    </div>
  </form>

  @push('scripts')
    <script>
      FilePond.registerPlugin(FilePondPluginImagePreview);
      
      const inputElement = document.querySelector('input[id="photo"]');
      const pond = FilePond.create(inputElement);

      FilePond.setOptions({
        server: {
          // url: '/tmp_upload',
          process: '/tmp_upload',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        }
      });
    </script>
  @endpush
</x-guest-layout>

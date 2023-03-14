<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Posts') }}
    </h2>
  </x-slot>

  <div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      @if (session()->has('success'))
      <div class="bg-green-400 text-sm text-green-700 m-2 p-2">
        {{ session('success') }}
      </div>
      @endif
      @if (session()->has('danger'))
      <div class="bg-red-400 text-sm text-red-700 m-2 p-2">
        {{ session('danger') }}
      </div>
      @endif

    </div>
  </div>

  {{-- Listado de Posts --}}
  <div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 text-gray-900">

          <form method='post' action="{{ route('posts.update', $post) }}" class="incline">
            @csrf
            @method('PUT')

            <div>
              <x-input-label for="title" :value="__('Título')" />
              <x-text-input id="title" class="block mt-1 w-full bottom-4" type="text" name="title"
                :value="old('title', $post->title)" autofocus autocomplete="title" />
              <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <!-- Photo -->
            <div class="mt-4">
              <x-input-label for="photo" :value="__('Fotografía')" />

              <input type="file" name="photo" id="filePond" class="w-48 left-5"
                :value="{{ Storage::disk('public')->url('posts/' . $post->photo) }}" accept="image/*">

              <x-input-error :messages="$errors->get('photo')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
              <x-primary-button class="ml-4">
                {{ __('Guardar cambios') }}
              </x-primary-button>
            </div>
            
            <div>
              <a href="{{ url()->previous() }}" style="float:right;"
                class="mb-4 mt-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Cancelar</a>
            </div>


            {{-- <div action="{}" class="flex items-center justify-end mt-4">
              <x-primary-button class="ml-4">
                {{ __('Cancelar') }}
              </x-primary-button>
            </div> --}}

          </form>



        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script type="module">
    // Registrar el plugin
      FilePond.registerPlugin(FilePondPluginImagePreview);
      
      const inputElement = document.querySelector('input[id="filePond"]');
      const pond = FilePond.create(inputElement);

      FilePond.setOptions({
        server: {
          process: '/tmp_upload',
          revert: '/tmp_delete',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        }
      });
  </script>
  @endpush
  </x-guest-layout>
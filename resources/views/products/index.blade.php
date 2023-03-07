<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Products') }}
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

      <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
          <x-input-label for="name" :value="__('Nombre')" />
          <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" autofocus
            autocomplete="name" />
          <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
          <x-input-label for="description" :value="__('Descripción')" />
          <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" autofocus
            autocomplete="description" />
          <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <!-- Photo -->
        <div class="mt-4">
          <label for="document">Photo</label>
          <div class="needsclick dropzone" id="document-dropzone">
 
          </div>
        </div>

        <div class="flex items-center justify-end mt-4">
          <x-primary-button class="ml-4">
            {{ __('Guardar Datos') }}
          </x-primary-button>
        </div>
      </form>
    </div>
  </div>

  {{-- Listado de Products --}}
  <div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 text-gray-900">
          <table class="border-collapse table-auto w-full text-sm">
            <thead>
              <tr>
                <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">Nombre</th>
                <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">Descripción</th>
                <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">Created At</th>
                <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">Updated At</th>
                <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">Updated At</th>
                <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">Action</th>
              </tr>
            </thead>
            <tbody class="bg-white">
              @foreach ($products as $item)
                <tr>
                  <td
                    class="border-b border-slate-100 dark:border-slate-700 p-2 pl-8 text-slate-500 dark:text-slate-400">
                    {{ $item->name }}
                  </td>
                  <td
                    class="border-b border-slate-100 dark:border-slate-700 p-2 pl-8 text-slate-500 dark:text-slate-400">
                    {{ $item->description }}
                  </td>
                  <td
                    class="border-b border-slate-100 dark:border-slate-700 p-2 pl-8 text-slate-500 dark:text-slate-400">
                    {{ $item->created_at }}
                  </td>
                  <td
                    class="border-b border-slate-100 dark:border-slate-700 p-2 pl-8 text-slate-500 dark:text-slate-400">
                    {{ $item->updated_at }}
                  </td>
                  <td class="m-5 p-5 flex flex-row items-center">
                    @foreach ($item->media as $image)
                      <img src="{{ $image->getUrl('thumb') }}" alt="Imagen no encontrada">
                    @endforeach
                  </td>
                  <td
                    class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                    <a href="#"
                      class="border border-blue-500 hover:bg-blue-500 hover:text-white px-4 py-2 rounded-md">SHOW</a>
                    <a href="#"
                      class="border border-yellow-500 hover:bg-yellow-500 hover:text-white px-4 py-2 rounded-md">EDIT</a>
                    {{-- add delete button using form tag --}}
                    <form method="post" action="#" class="inline">
                      @csrf
                      @method('delete')

                      <button
                        class="border border-red-500 hover:bg-red-500 hover:text-white px-4 py-2 rounded-md">
                        {{ __('Delete') }}
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @push('styles')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
  @endpush

  @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>

    <script>
      var uploadedDocumentMap = {}
      Dropzone.options.documentDropzone = {
        url: '{{ route('products.storeMedia') }}',
        maxFilesize: 2, // MB
        addRemoveLinks: true,
        acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf",
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function(file, response) {
          $('form').append('<input type="hidden" name="photo[]" value="' + response.name + '">')
          uploadedDocumentMap[file.name] = response.name
        },
        removedfile: function(file) {
          file.previewElement.remove()
          var name = ''
          if (typeof file.file_name !== 'undefined') {
              name = file.file_name
          } else {
              name = uploadedDocumentMap[file.name]
          }
          $('form').find('input[name="photo[]"][value="' + name + '"]').remove()
        }
      }
    </script>
  @endpush
</x-guest-layout>
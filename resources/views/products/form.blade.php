<x-app-layout>
  <x-slot:header>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{-- {{ __('Products - Dropzone') }} --}}
      {{ isset($product) ? __('Edit Product') : __('Create Product') }}
    </h2>
  </x-slot>

  <div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
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

          {{-- <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf --}}
          <form method="post" action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
            @csrf
            {{-- add @method('put') for edit mode --}}
            @isset($product)
              @method('put')
            @endisset

            <div>
              <x-input-label for="name" :value="__('Name')" />
              <x-text-input type="text" id="name" name="name" class="block mt-1 w-full" :value="$product->name ?? old('name')" autofocus autocomplete="name" />
              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
              <x-input-label for="description" :value="__('Description')" />
              <x-textarea-input id="description" name="description" class="block mt-1 w-full" autocomplete="description">{{ $product->description ?? old('description') }}</x-textarea-input>
              <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- Photo -->
            <div class="w-60 mt-4">
              <x-input-label for="document" :value="__('Photo')" />
              <div class="needsclick dropzone" id="document-dropzone" accept="image/*"></div>
            </div>

            <div class="flex items-center justify-end mt-4">
              <x-primary-button class="ml-4">
                {{ __('Save') }}
              </x-primary-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
  @endpush

  @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>

    <script>
      let uploadedDocumentMap = {}

      Dropzone.options.documentDropzone = {
        url: '{{ route('products.storeMedia') }}',
        maxFilesize: 2, // MB
        addRemoveLinks: true,
        // acceptedFiles: ".jpeg,.jpg,.png,.gif.,pdf",
        acceptedFiles: 'image/*',
        maxFiles: 1,
        dictDefaultMessage: "<h3 class='sbold'>Suelte los archivos aquí o haga clic para cargar el documento<h3>",
        dictRemoveFile:'Quitar',
        // paramName: 'image',     // Cambiar 'file' por 'image'
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
        },

        init: function() {
          @if (isset($photos))
            var files = {!! json_encode($photos) !!}
            for (var i in files) {
              var file = files[i]
              console.log(file);

              file = {
                ...file,
                width: 226,
                height: 324
              }
              this.options.addedfile.call(this, file)
              this.options.thumbnail.call(this, file, file.original_url)
              file.previewElement.classList.add('dz-complete')

              $('form').append('<input type="hidden" name="photo[]" value="' + file.file_name + '">')
            }
          @endif
        }
      }
    </script>
  @endpush
</x-guest-layout>
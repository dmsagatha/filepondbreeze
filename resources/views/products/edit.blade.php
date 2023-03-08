<x-app-layout>
  <x-slot:header>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Products') }} - Dropzone
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

          <form action="{{ route('products.update', ['id' => $product->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input name="name" value="{{ old('name', $product->name) }}" type="text" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control"
                rows="3">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="mb-3">
              <label for="document">Photo</label>
              <div class="needsclick dropzone" id="document-dropzone">

              </div>
            </div>

            <div class="flex items-center justify-end mt-4">
              <x-primary-button class="ml-4">
                {{ __('Actualizar Datos') }}
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
      var uploadedDocumentMap = {}

      Dropzone.options.documentDropzone = {
        url: '{{ route('products.storeMedia') }}',
        maxFilesize: 2, // MB
        addRemoveLinks: true,
        acceptedFiles: ".jpeg,.jpg,.png,.gif.,pdf",
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
</x-app-layout>
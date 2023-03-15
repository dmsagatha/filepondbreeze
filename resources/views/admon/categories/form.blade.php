<x-app-layout>
  <x-slot:header>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ isset($category) ? __('Edit') : __('Create') }}
    </h2>
  </x-slot>

  <div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <form method="post" action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
            @csrf
            @isset($category)
              @method('put')
            @endisset

            <div>
              <x-input-label for="name" :value="__('Name')" />
              <x-text-input type="text" id="name" name="name" class="block mt-1 w-full" :value="$category->name ?? old('name')" autofocus autocomplete="name" />
              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Photo -->
            <div class="w-60 mt-4">
              @if (!isset($category->featured_image))
                <x-input-label for="featured_image" :value="__('Photo')" />

                <div class="dropzone" id="dropzone"></div>
                <input type="hidden" readonly class="newimage" name="featured_image" value="">
              @else
                <x-input-label for="featured_image" :value="__('Photo')" />

                <div class="dropzone" id="dropzone">
                  <img id="img" src="{{ isset($category) ? asset('storage/categories/' . $category->featured_image) : '' }}" class="w-30 h-30 rounded-lg" alt="{{ $category->featured_image }}" />
                </div>
                <input type="hidden" readonly class="newimage" name="featured_image" value="">
              @endif
            </div>

            {{-- <div class="w-60 mt-4">
              <x-input-label for="featured_image" :value="__('Photo')" />

              <div class="dropzone" id="dropzone">
                <img id="img" src="{{ isset($category) ? asset('storage/categories/' . $category->featured_image) : '' }}" class="w-30 h-30 rounded-lg" alt="{{ $category->featured_image }}" />
              </div>
              <input type="hidden" readonly class="newimage" name="featured_image" value="">
            </div> --}}

            <div class="flex items-center justify-end mt-4">
              <x-primary-button class="ml-4">
                {{ isset($category->id) ? __('Uptade') : __('Save') }}
              </x-primary-button>
              <x-blue-button class="ml-4">
                <a href="{{ route('categories.index') }}">{{ __('Cancel') }}</a>
              </x-blue-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @push('styles')
    <link rel="stylesheet" href="{{ asset('css/dropzone.min.css') }}">
  @endpush

  @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/dropzone.min.js') }}"></script>

    <script>
      let newimage = [];
      Dropzone.autoDiscover = false;

      let myDropzone = new Dropzone("#dropzone", {
        url: '{{ route('dropzone.store') }}',
        maxFilesize: 2, // MB
        maxFiles: 1,
        addRemoveLinks: true,
        acceptedFiles: 'image/*',
        parallelUploads: 1,
        uploadMultiple: true,
        paramName: 'featured_image', // Cambiar 'file' por 'featured_image'
        dictDefaultMessage: "<h3 class='sbold'>Suelte los archivos aquí o haga clic para cargar el documento<h3>",
        dictRemoveFile:'Quitar',
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },

        success: function(file, response) {
          console.log(file);
          newimage.push(response);
          console.log(newimage);
          $(".newimage").val(newimage);
          $(file.previewTemplate).find('.dz-filename span').data('dz-name', response);
          $(file.previewTemplate).find('.dz-filename span').html(response);
        },

        removedfile: function(file) {
          let removeimageName = $(file.previewElement).find('.dz-filename span').data('dz-name');
          $.ajax({
            type: 'POST',
            url: "{{ route('remove.file') }}",
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
              removeimageName: removeimageName
            },
            success: function(data) {
              console.log(data);
              for (var i = 0; i < newimage.length; i++) {
                if (newimage[i] === data) {
                  newimage.splice(i, 1);
                }
              }
              $(".newimage").val(newimage);
            }
          });
          var _ref;
          return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
        },

        init: function (file) {
          this.on("maxfilesexceeded", function(file) {
            this.removeAllFiles();
            this.addFile(file);
          });

          this.on("addedfile", function(file) {
            $('#img').remove();
          });
        }
      });
    </script>
  @endpush
</x-app-layout>
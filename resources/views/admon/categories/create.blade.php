<x-app-layout>
  <x-slot:header>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ isset($category) ? __('Edit') : __('Create') }}
    </h2>
  </x-slot>

  <div class="py-2">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="py-1 px-4 text-gray-900">
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

          <form method="post" action="{{ route('categories.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
            @csrf

            <div>
              <x-input-label for="name" :value="__('Name')" />
              <x-text-input type="text" id="name" name="name" class="block w-full" :value="$category->name ?? old('name')"
                autofocus autocomplete="name" />
              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Photo -->
            <div class="w-60 mt-4">
              <x-input-label for="featured_image" :value="__('Photo')" />

              <div class="dropzone" id="dropzone"></div>
              <input type="hidden" readonly class="newimage" name="featured_image" value="">
            </div>

            <div class="flex items-center justify-end mt-2">
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
        }
      });
    </script>
  @endpush
</x-guest-layout>
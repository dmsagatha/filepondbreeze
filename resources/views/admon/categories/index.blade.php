<x-app-layout>
  <x-slot:header>
    <div class="flex justify-between">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Categories') }} ({{ count($categories) }})
      </h2>
      <a href="{{ route('categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md">{{ __('Create') }}</a>
    </div>
  </x-slot>

  {{-- Listado de Categorías --}}
  <div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
        
        @if(session('updatemsg'))
          <script>
            swal("Movie updated Successfully -:)");
          </script>
        @endif

        <div class="p-4 text-gray-900">
          @if ($categories->count())
            <table class="border-collapse table-auto w-full text-sm">
              <thead>
                <tr>
                  <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">{{ __('Name') }}</th>
                  <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">{{ __('Created At') }}</th>
                  <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">{{ __('Updated At') }}</th>
                  <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">{{ __('Photo') }}</th>
                  <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">{{ __('Actions') }}</th>
                </tr>
              </thead>
              <tbody class="bg-white">
                @foreach ($categories as $item)
                  <tr>
                    <td class="border-b border-slate-100 dark:border-slate-700 p-2 pl-8 text-slate-500 dark:text-slate-400">
                      {{ $item->name }}
                    </td>
                    <td class="border-b border-slate-100 dark:border-slate-700 p-2 pl-8 text-slate-500 dark:text-slate-400">
                      {{ $item->created_at }}
                    </td>
                    <td class="border-b border-slate-100 dark:border-slate-700 p-2 pl-8 text-slate-500 dark:text-slate-400">
                      {{ $item->updated_at }}
                    </td>
                    <td class="m-5 p-5 flex flex-row items-center">
                      <img src="{{ isset($item) ? asset('/categories/'.$item->featured_image) : '' }}" class="w-10 h-10 rounded-lg" alt="{{ ($item->featured_image) }}" />

                      {{-- <img src="{{ asset('/categories/'.$item->featured_image) }}" title="{{ ($item->featured_image) }}" class="w-10 h-10 px-1 rounded-full" alt=""> --}}
                    </td>
                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 px-2 text-slate-500 dark:text-slate-400">
                      <div class="flex items-stretch space-x-1">
                        <a href="#" class="border border-blue-500 hover:bg-blue-500 hover:text-white px-4 py-2 rounded-md">{{ __('View') }}</a>

                        <a href="{{ route('categories.edit', $item) }}" class="border border-yellow-500 hover:bg-yellow-500 hover:text-white px-4 py-2 rounded-md">{{ __('Edit') }}</a>

                        {{-- add delete button using form tag --}}
                        <form method="post" action="{{ route('categories.destroy', $item) }}" class="inline">
                          @csrf @method('delete')

                          <button
                            class="border border-red-500 hover:bg-red-500 hover:text-white px-4 py-2 rounded-md">
                            {{ __('Delete') }}
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @else
            <div class="flex justify-center px-4 mt-14 mb-2 space-x-4 text-green-600">
              No hay registros creados
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    {{-- @if (session()->has('success')) --}}
    {{-- @if(session('success'))
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          Swal.fire('Registro creado!')
        });
      </script>
    @endif --}}
  @endpush
</x-app-layout>
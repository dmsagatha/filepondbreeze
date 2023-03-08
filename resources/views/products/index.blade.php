<x-app-layout>
  <x-slot:header>
    <div class="flex justify-between">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Products') }} - Dropzone
      </h2>
      <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md">{{ __('Create') }}</a>
    </div>
  </x-slot>

  {{-- Listado de Productos --}}
  <div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 text-gray-900">
          <table class="border-collapse table-auto w-full text-sm">
            <thead>
              <tr>
                <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">{{ __('Name') }}</th>
                <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">{{ __('Description') }}</th>
                <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">{{ __('Created At') }}</th>
                <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">{{ __('Updated At') }}</th>
                <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">{{ __('Photo') }}</th>
                <th class="border-b font-medium p-2 pl-8 pt-0 pb-3 text-slate-400 text-left">{{ __('Actions') }}</th>
              </tr>
            </thead>
            <tbody class="bg-white">
              @foreach ($products as $item)
                <tr>
                  <td class="border-b border-slate-100 dark:border-slate-700 p-2 pl-8 text-slate-500 dark:text-slate-400">
                    {{ $item->name }}
                  </td>
                  <td class="border-b border-slate-100 dark:border-slate-700 p-2 pl-8 text-slate-500 dark:text-slate-400">
                    {{ $item->description }}
                  </td>
                  <td class="border-b border-slate-100 dark:border-slate-700 p-2 pl-8 text-slate-500 dark:text-slate-400">
                    {{ $item->created_at }}
                  </td>
                  <td class="border-b border-slate-100 dark:border-slate-700 p-2 pl-8 text-slate-500 dark:text-slate-400">
                    {{ $item->updated_at }}
                  </td>
                  <td class="m-5 p-5 flex flex-row items-center">
                    @foreach ($item->media as $image)
                      <img src="{{ $image->getUrl() }}" alt="Imagen no encontrada">
                    @endforeach
                  </td>
                  <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                    <a href="#" class="border border-blue-500 hover:bg-blue-500 hover:text-white px-4 py-2 rounded-md">{{ __('View') }}</a>

                    <a href="{{ route('products.edit', $item) }}" class="border border-yellow-500 hover:bg-yellow-500 hover:text-white px-4 py-2 rounded-md">{{ __('Edit') }}</a>

                    {{-- add delete button using form tag --}}
                    <form method="post" action="{{ route('products.destroy', $item) }}" class="inline">
                      @csrf @method('delete')

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
</x-app-layout>
<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-2 py-2 bg-blue-600 border border-transparent rounded-md font-semibold uppercase tracking-widest text-xs text-white hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition']) }}>
  {{ $slot }}
</button>
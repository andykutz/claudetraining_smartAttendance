<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-rose-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest shadow-md shadow-red-900/20 hover:from-red-400 hover:to-rose-500 active:from-red-600 active:to-rose-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-navy-950 transition-all duration-150']) }}>
    {{ $slot }}
</button>

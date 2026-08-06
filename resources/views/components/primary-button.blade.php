<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest shadow-md shadow-blue-900/20 hover:from-blue-500 hover:via-indigo-500 hover:to-blue-700 hover:shadow-lg hover:-translate-y-px active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-navy-950 transition-all duration-150']) }}>
    {{ $slot }}
</button>

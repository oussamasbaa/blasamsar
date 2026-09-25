<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3 bg-white/5 border border-white/10 rounded-xl font-bold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 ring-offset-black disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

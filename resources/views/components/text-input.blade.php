@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-black border-white/20 text-white focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm']) }}>

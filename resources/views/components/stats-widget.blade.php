@props(['title', 'value', 'icon', 'color' => 'blue'])

<div class="glass-card p-6 flex items-center gap-4">
    <div class="w-12 h-12 rounded-2xl bg-{{ $color }}-100 flex items-center justify-center text-{{ $color }}-600">
        {{ $icon }}
    </div>
    <div>
        <p class="text-sm font-medium text-slate-500">{{ $title }}</p>
        <p class="text-2xl font-bold text-slate-800">{{ $value }}</p>
    </div>
</div>

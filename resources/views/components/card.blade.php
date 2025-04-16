<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-400 bg-white p-4']) }}>
    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-200">
        {{ $icon ?? '❔' }}
    </div>
    <div class="mt-5">
        <span class="text-sm font-medium text-gray-800 ">
            {{ $title }}
        </span>
        <h4 class="mt-2 text-xl font-bold text-gray-800 ">
            {{ $slot }}
        </h4>
    </div>
</div>

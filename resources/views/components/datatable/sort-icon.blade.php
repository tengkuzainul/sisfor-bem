@props(['column'])

<span class="ml-1 inline-flex">
    <x-heroicon-s-chevron-up x-show="sortBy === '{{ $column }}' && sortDir === 'asc'" x-cloak class="h-3.5 w-3.5 text-primary-500" />
    <x-heroicon-s-chevron-down x-show="sortBy === '{{ $column }}' && sortDir === 'desc'" x-cloak class="h-3.5 w-3.5 text-primary-500" />
    <x-heroicon-o-chevron-up-down x-show="sortBy !== '{{ $column }}'" class="h-3.5 w-3.5 text-gray-300 dark:text-gray-600" />
</span>

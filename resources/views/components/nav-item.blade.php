@props(['route', 'icon', 'label', 'active' => false])

{{-- The active state is carried by three things at once: a tinted fill, the
     brand rail drawn by .nav-item::before, and the accent icon. The rail is
     what survives when the sidebar collapses to icons only. --}}
<a href="{{ route($route) }}" @if($active) aria-current="page" @endif
   class="nav-item flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium transition-[background-color,color,transform] duration-150
          {{ $active
              ? 'bg-accent/10 text-zinc-900 dark:bg-accent/15 dark:text-zinc-50'
              : 'text-zinc-500 hover:translate-x-0.5 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-200' }}">
    <x-icon :name="$icon"
        class="size-4 shrink-0 transition-colors {{ $active ? 'text-accent-content' : 'text-zinc-400 group-hover:text-zinc-500 dark:text-zinc-500' }}" />
    <span class="sidebar-label truncate">{{ $label }}</span>
    @if ($active)
        <span class="sidebar-active-dot ml-auto size-1.5 shrink-0 rounded-full"
              style="background-image: linear-gradient(135deg, var(--color-brand-from), var(--color-brand-to));"></span>
    @endif
</a>

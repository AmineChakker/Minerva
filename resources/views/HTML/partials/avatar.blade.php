{{--
  Avatar partial — shows profile photo or initials fallback.
  Variables:
    $user      - User model (required)
    $size      - Tailwind size class, default 'size-9'
    $textSize  - Tailwind text size, default 'text-sm'
    $color     - Color name (primary/success/info/warning), default 'primary'
--}}
@php
  $size     = $size     ?? 'size-9';
  $textSize = $textSize ?? 'text-sm';
  $color    = $color    ?? 'primary';
@endphp

@if($user->profile_photo)
  <img src="{{ Storage::url($user->profile_photo) }}"
       class="{{ $size }} rounded-full object-cover ring-2 ring-white dark:ring-default-200"
       alt="{{ $user->full_name }}">
@else
  <div class="{{ $size }} rounded-full bg-{{ $color }}/10 flex items-center justify-center {{ $textSize }} font-bold text-{{ $color }} flex-shrink-0">
    {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
  </div>
@endif

<img 
    src="{{ asset('images/logo.png') }}" 
    alt="{{ config('app.name') }} Logo" 
    {{-- BARIS INI YANG MENGATUR UKURAN --}}
    {{ $attributes->merge(['class' => 'h-20 w-20']) }} 
/>
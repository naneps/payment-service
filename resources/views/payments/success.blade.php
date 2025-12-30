@extends('layouts.app')

@section('title', 'Payment Success')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-green-100">
    <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md text-center">

        {{-- ICON --}}
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center animate-bounce">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        {{-- TEXT --}}
        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            Payment Successful 🎉
        </h1>

        <p class="text-gray-600 mb-6">
            We are verifying your payment.<br>
            Please wait a moment…
        </p>

        {{-- LOADING BAR --}}
        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
            <div class="bg-green-500 h-2 animate-progress"></div>
        </div>

        <p class="text-xs text-gray-400 mt-4">
            Order ID: {{ $orderId }}
        </p>
    </div>
</div>

{{-- AUTO REDIRECT --}}
<script>
    setTimeout(() => {
        window.location.href = '/'; // atau ke dashboard / receipt
    }, 3000);
</script>

{{-- Tailwind custom animation --}}
<style>
@keyframes progress {
    0% { width: 0%; }
    100% { width: 100%; }
}
.animate-progress {
    animation: progress 3s linear forwards;
}
</style>
@endsection

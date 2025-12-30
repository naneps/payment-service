@extends('layouts.app')

@section('title', 'Payment Report')

@section('content')
{{-- Custom CSS for Entrance Animation --}}
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeInUp 0.5s ease-out forwards;
    }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
</style>

<div class="container mx-auto px-4 py-8 max-w-7xl">

    {{-- HEADER & FILTERS --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 animate-fade-in">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Payment Report</h1>
                <p class="text-sm text-gray-500 mt-1">Monitor and manage transaction histories.</p>
            </div>
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            {{-- Search Input --}}
            <div class="md:col-span-5 relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    name="q"
                    value="{{ $filters['q'] ?? '' }}"
                    placeholder="Search by External Ref ID..."
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 sm:text-sm"
                />
            </div>

            {{-- Status Select --}}
            <div class="md:col-span-3">
                <select name="status" class="block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg cursor-pointer bg-gray-50 hover:bg-white transition-colors">
                    <option value="">All Statuses</option>
                    <option value="PENDING" {{ ($filters['status'] ?? '') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="SUCCESS" {{ ($filters['status'] ?? '') == 'SUCCESS' ? 'selected' : '' }}>Success</option>
                    <option value="FAILED" {{ ($filters['status'] ?? '') == 'FAILED' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            {{-- Channel Select --}}
            <div class="md:col-span-2">
                <select name="channel" class="block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg cursor-pointer bg-gray-50 hover:bg-white transition-colors">
                    <option value="">All Channels</option>
                    <option value="QRIS" {{ ($filters['channel'] ?? '') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                    <option value="SNAP" {{ ($filters['channel'] ?? '') == 'SNAP' ? 'selected' : '' }}>SNAP</option>
                </select>
            </div>

            {{-- Filter Button --}}
            <div class="md:col-span-2">
                <button class="w-full flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm hover:shadow-md transform active:scale-95 transition-all duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Data
                </button>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in delay-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reference</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Channel</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Paid At</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($payments as $p)
                        <tr class="hover:bg-blue-50/50 transition-colors duration-200 group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-gray-100 p-1.5 rounded text-gray-500 mr-3">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-mono font-medium text-gray-700 group-hover:text-blue-600 transition-colors">
                                        {{ $p->external_ref }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded-md border border-gray-200">
                                    {{ $p->method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm font-bold text-gray-900">
                                    Rp {{ number_format($p->amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $statusClasses = match($p->status->value) {
                                        'SUCCESS' => 'bg-green-100 text-green-700 border-green-200',
                                        'PENDING' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                        'FAILED'  => 'bg-red-100 text-red-700 border-red-200',
                                        default   => 'bg-gray-100 text-gray-700 border-gray-200',
                                    };
                                    $statusIcon = match($p->status->value) {
                                        'SUCCESS' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />',
                                        'PENDING' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                        'FAILED'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />',
                                        default   => '',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusClasses }}">
                                    <svg class="mr-1.5 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        {!! $statusIcon !!}
                                    </svg>
                                    {{ $p->status->value }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">
                                    {{ $p->paid_at?->format('d M Y') ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $p->paid_at?->format('H:i') ?? '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">
                                    {{ $p->created_at->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $p->created_at->format('H:i') }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-50 rounded-full p-4 mb-3">
                                        <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900">No payments found</h3>
                                    <p class="text-gray-500 mt-1">Try adjusting your filters or search query.</p>
                                    <a href="{{ url()->current() }}" class="mt-4 text-blue-600 hover:text-blue-800 text-sm font-medium hover:underline">
                                        Clear all filters
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer / Pagination --}}
        @if($payments->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $payments->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

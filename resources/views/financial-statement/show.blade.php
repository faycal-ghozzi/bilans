@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
<div class="container mx-auto px-10">
    <div class="bg-white p-8 shadow-md rounded-lg ">
    <h1 class="text-2xl font-bold text-center mb-6">État Financier de {{ $file->company->name }}</h1>
    <p class="mb-4 text-center"><strong>{{ $file->date }}</strong></p>
        <!-- Table Headers -->
    <div class="grid grid-cols-3 gap-4 items-center">
        <div class="text-lg font-semibold text-center col-start-2">N</div>
        <div class="text-lg font-semibold text-center">N-1</div>
    </div>
    @foreach ($categories as $category => $entries)
    <section class="mb-6">
        <!-- Category Header -->
        <h2 class="text-xl font-bold mb-4">{{ $category }}</h2>

        <!-- Entries -->
        @php
            $previousEntry = null;
        @endphp
        @foreach ($entries as $entry)
        <div class="grid grid-cols-3 gap-4 items-center {{ $entry->decoration == 'stripe' ? 'bg-gray-200' : '' }}">
            <!-- Label -->
            <div class="{{ $entry->decoration == 'bold' ? 'font-bold' : '' }}">
                {{ $entry->label }}
            </div>
            
            <!-- Current Year Value (n) -->
            <div class="text-center {{ $entry->decoration == 'bold' ? 'font-bold' : '' }}">
                @php
                    $currentValue = isset($financialStatements[$dateCurrentYear])
                        ? $financialStatements[$dateCurrentYear]->firstWhere('entry_point_id', $entry->id)->value ?? null
                        : null;
                @endphp
                @if (str_contains($entry->label, 'Amortissements') || str_contains($entry->label, 'Provisions'))
                    ({{ $currentValue !== null ? number_format($currentValue, 3, '.', ' ') : '-' }})
                @else
                    {{ $currentValue !== null ? number_format($currentValue, 3, '.', ' ') : '-' }}
                @endif
            </div>

            <!-- Previous Year Value (n-1) -->
            <div class="text-center {{ $entry->decoration == 'bold' ? 'font-bold' : '' }}">
                @php
                    $previousValue = isset($financialStatements[$datePreviousYear])
                        ? $financialStatements[$datePreviousYear]->firstWhere('entry_point_id', $entry->id)->value ?? null
                        : null;
                @endphp
                @if (str_contains($entry->label, 'Amortissements') || str_contains($entry->label, 'Provisions'))
                    ({{ $previousValue !== null ? number_format($previousValue, 3, '.', ' ') : '-' }})
                @else
                    {{ $previousValue !== null ? number_format($previousValue, 3, '.', ' ') : '-' }}
                @endif
            </div>
        </div>

        <!-- Result Row for Amortissements or Provisions -->
        @if (str_contains($entry->label, 'Amortissements') || str_contains($entry->label, 'Provisions'))
        <div class="grid grid-cols-3 gap-4 items-center mb-6">
            <!-- Empty Label -->
            <div></div>

            <!-- Result (n - corresponding Immobilisation) -->
            <div class="text-center font-bold">
                @php
                    $currentResult = $currentValue !== null && $previousEntry !== null && isset($previousEntry['currentValue'])
                        ? $previousEntry['currentValue'] - $currentValue
                        : null;
                @endphp
                {{ $currentResult !== null ? number_format($currentResult, 3, '.', ' ') : '-' }}
            </div>

            <!-- Result (n-1 - corresponding Immobilisation for previous year) -->
            <div class="text-center font-bold">
                @php
                    $previousResult = $previousValue !== null && $previousEntry !== null && isset($previousEntry['previousValue'])
                        ? $previousEntry['previousValue'] - $previousValue
                        : null;
                @endphp
                {{ $previousResult !== null ? number_format($previousResult, 3, '.', ' ') : '-' }}
            </div>
        </div>
        @endif

        <!-- Track Previous Entry -->
        @php
            $previousEntry = [
                'id' => $entry->id,
                'label' => $entry->label,
                'currentValue' => $currentValue,
                'previousValue' => $previousValue
            ];
        @endphp
        @endforeach
    </section>
    @endforeach
    </div>
</div>
@endsection

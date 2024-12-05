@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar', ['id' => $file->id])
@endsection

@section('content')
<div class="container mx-auto px-10">
    <div class="bg-white p-8 shadow-md rounded-lg">
        <h1 class="text-2xl font-bold text-center mb-6">État Financier de {{ $file->company->name }}</h1>
        <p class="mb-4 text-center"><strong>{{ $file->date }}</strong></p>
        <div class="grid grid-cols-5 gap-4 items-center font-semibold text-center">
            <div>Label</div>
            <div>N</div>
            <div>N-1</div>
            <div>Évolution Absolue</div>
            <div>Évolution en %</div>
        </div>
        @foreach ($categories as $category => $entries)
        <section class="mb-6">
            <h2 class="text-xl font-bold mb-4">{{ $category }}</h2>

            @php
                $previousEntry = null;
            @endphp
            @foreach ($entries as $entry)
            <div 
                class="grid grid-cols-5 gap-4 items-center 
                    {{ $entry->decoration == 'stripe' ? 'bg-btlGreen text-white' : ($loop->even ? 'bg-gray-100' : 'bg-white') }}">
                
                <!-- Label -->
                <div class="{{ $entry->decoration == 'bold' ? 'font-bold' : '' }}">
                    {{ $entry->label }}
                </div>

                <!-- Current Year Value (N) -->
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

                <!-- Previous Year Value (N-1) -->
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

                <!-- Absolute Evolution -->
                <div class="text-center">
                    @php
                        $absoluteEvolution = $currentValue !== null && $previousValue !== null 
                            ? $currentValue - $previousValue 
                            : null;
                    @endphp
                    {{ $absoluteEvolution !== null ? number_format($absoluteEvolution, 3, '.', ' ') : '-' }}
                </div>

                <!-- Percentage Evolution -->
                <div class="text-center">
                    @php
                        $percentageEvolution = $currentValue !== null && $previousValue !== null && $previousValue != 0
                            ? (($currentValue - $previousValue) / $previousValue) * 100
                            : null;
                    @endphp
                    {{ $percentageEvolution !== null ? number_format($percentageEvolution, 2, '.', ' ') . ' %' : '-' }}
                </div>
            </div>

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

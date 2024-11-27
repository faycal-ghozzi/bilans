@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <!-- Metrics Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Companies -->
        <div class="bg-btlGreen text-white shadow-lg rounded-lg p-6 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold mb-4">Total Companies</h2>
                <p class="text-4xl font-bold">{{ $totalCompanies }}</p>
            </div>
            <div>
                <i class="fas fa-building text-5xl opacity-50"></i>
            </div>
        </div>
        <!-- Total Financial Statements -->
        <div class="bg-blue-600 text-white shadow-lg rounded-lg p-6 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold mb-4">Total Financial Statements</h2>
                <p class="text-4xl font-bold">{{ $totalFinancialStatements }}</p>
            </div>
            <div>
                <i class="fas fa-file-alt text-5xl opacity-50"></i>
            </div>
        </div>
        <!-- Total Users -->
        <div class="bg-indigo-600 text-white shadow-lg rounded-lg p-6 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold mb-4">Total Users</h2>
                <p class="text-4xl font-bold">{{ $totalUsers }}</p>
            </div>
            <div>
                <i class="fas fa-users text-5xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Graph Section -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Financial Statements Overview</h2>
            <div class="flex items-center gap-4">
                <!-- Animated Switch -->
                <form id="viewTypeForm" action="{{ route('dashboard') }}" method="GET" class="flex items-center">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium">Yearly</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="viewTypeToggle" class="sr-only peer" 
                                {{ $viewType === 'month' ? 'checked' : '' }}>
                            <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-btlGreen rounded-full peer peer-checked:bg-btlGreen transition-all"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full peer peer-checked:translate-x-6 transition-transform"></div>
                        </label>
                        <label class="text-sm font-medium">Monthly</label>
                    </div>
                    <input type="hidden" name="viewType" value="{{ $viewType === 'month' ? 'month' : 'year' }}">
                </form>

                <!-- Year Dropdown for Monthly View -->
                @if ($viewType === 'month')
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center ml-4">
                    <input type="hidden" name="viewType" value="month">
                    <label for="selectedYear" class="mr-2">Year:</label>
                    <select name="selectedYear" id="selectedYear" class="border-gray-300 rounded" onchange="this.form.submit()">
                        @for ($year = $minYear; $year <= $maxYear; $year++)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </form>
                @endif
            </div>
        </div>
        <canvas id="financialStatementsChart" style="max-height: 400px;"></canvas>
    </div>
</div>

<!-- Chart.js Integration -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('viewTypeToggle');
        const form = document.getElementById('viewTypeForm');

        // Handle toggle switch
        toggle.addEventListener('change', function () {
            const isMonthly = toggle.checked;
            const viewType = isMonthly ? 'month' : 'year';
            form.action = `{{ route('dashboard') }}?viewType=${viewType}`;
            form.submit();
        });

        // Chart data and labels
        const ctx = document.getElementById('financialStatementsChart').getContext('2d');
        const labels = @json($labels);
        const data = @json($chartData);

        // Create a new chart instance
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '{{ $viewType === "month" ? "Monthly Financial Statements" : "Yearly Financial Statements" }}',
                    data: data,
                    backgroundColor: 'rgba(23, 48, 35, 0.8)',
                    borderColor: 'rgba(23, 48, 35, 1)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.raw} Statements`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: '{{ $viewType === "month" ? "Months" : "Years" }}',
                        },
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Statements',
                        },
                        ticks: {
                            stepSize: 1,
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

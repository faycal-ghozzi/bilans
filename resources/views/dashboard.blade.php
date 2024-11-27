@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-center">Tableau de bord</h1>

    <!-- Metrics Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Companies -->
        <div class="bg-btlGreen text-white shadow-lg rounded-lg p-6 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold mb-4">Total des entreprises</h2>
                <p class="text-4xl font-bold">{{ $totalCompanies }}</p>
            </div>
            <div>
                <i class="fas fa-building text-5xl opacity-50"></i>
            </div>
        </div>
        <!-- Total Financial Statements -->
        <div class="bg-blue-600 text-white shadow-lg rounded-lg p-6 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold mb-4">États financiers totaux</h2>
                <p class="text-4xl font-bold">{{ $totalFinancialStatements }}</p>
            </div>
            <div>
                <i class="fas fa-file-alt text-5xl opacity-50"></i>
            </div>
        </div>
        <!-- Total Users -->
        <div class="bg-indigo-600 text-white shadow-lg rounded-lg p-6 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold mb-4">Nombre total d'utilisateurs</h2>
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
            <h2 class="text-lg font-semibold">Aperçu des états financiers</h2>
            <div class="flex items-center gap-4">
                <!-- Switch -->
                <div class="flex items-center">
                    <label class="switch">
                        <input type="checkbox" id="viewTypeToggle" {{ $viewType === 'month' ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                    <span class="ml-3 text-sm font-medium">
                        {{ $viewType === 'month' ? 'Mensuel' : 'Annuel' }}
                    </span>
                </div>
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
        <canvas id="financialStatementsChart" style="max-height: 600px;"></canvas>
    </div>
</div>

<!-- Chart.js Integration -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('viewTypeToggle');

        // Handle toggle switch
        toggle.addEventListener('change', function () {
            const isMonthly = toggle.checked;
            const viewType = isMonthly ? 'month' : 'year';
            const baseUrl = `{{ route('dashboard') }}`;
            window.location.href = `${baseUrl}?viewType=${viewType}`;
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
                    label: '{{ $viewType === "month" ? "États financiers mensuels" : "États financiers annuels" }}',
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
                                return context.raw === 1 ? `${context.raw} État financier` : `${context.raw} États financiers`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: '{{ $viewType === "month" ? "Mois" : "Années" }}',
                        },
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre d\'états financiers',
                        },
                        ticks: {
                            stepSize: 2,
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

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
            <div class="flex gap-4">
                <!-- Year Selector -->
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center">
                    <label for="year" class="mr-2">Year:</label>
                    <select name="year" id="year" class="border-gray-300 rounded" onchange="this.form.submit()">
                        @for ($year = $minYear; $year <= $maxYear; $year++)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                    <input type="hidden" name="groupBy" value="{{ $groupBy }}">
                </form>
                <!-- Group By Selector -->
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center">
                    <input type="hidden" name="year" value="{{ $selectedYear }}">
                    <label for="groupBy" class="mr-2">Group By:</label>
                    <select name="groupBy" id="groupBy" class="border-gray-300 rounded" onchange="this.form.submit()">
                        <option value="month" {{ $groupBy === 'month' ? 'selected' : '' }}>Month</option>
                        <option value="year" {{ $groupBy === 'year' ? 'selected' : '' }}>Year</option>
                    </select>
                </form>
            </div>
        </div>
        <canvas id="financialStatementsChart"></canvas>
    </div>

    <!-- Add Financial Statement Button -->
    <div class="flex justify-center">
        <a href="{{ url('/fs/add') }}" class="bg-btlGreen text-white font-bold py-4 px-8 rounded-lg shadow hover:bg-btlRed transition">
            + Ajouter Etat Financier
        </a>
    </div>
</div>

<!-- Chart.js Integration -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('financialStatementsChart').getContext('2d');

        // Chart data and labels
        const labels = {!! json_encode($groupBy === 'month' ? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] : range($minYear, $maxYear)) !!};
        const data = @json($chartData);

        // Chart configuration
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Financial Statements',
                    data: data,
                    backgroundColor: 'rgba(23, 48, 35, 0.8)',
                    borderColor: 'rgba(23, 48, 35, 1)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
                            text: '{{ $groupBy === "month" ? "Months" : "Years" }}',
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

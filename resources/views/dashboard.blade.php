@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <!-- Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Companies -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Total Companies</h2>
            <p class="text-4xl font-bold text-btlGreen">{{ $totalCompanies }}</p>
        </div>
        <!-- Total Financial Statements -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Total Financial Statements</h2>
            <p class="text-4xl font-bold text-btlGreen">{{ $totalFinancialStatements }}</p>
        </div>
        <!-- Total Users -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Total Users</h2>
            <p class="text-4xl font-bold text-btlGreen">{{ $totalUsers }}</p>
        </div>
    </div>

    <!-- Graph -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Financial Statements per Month ({{ \Carbon\Carbon::now()->year }})</h2>
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
    const ctx = document.getElementById('financialStatementsChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Financial Statements',
                data: @json($monthlyData),
                backgroundColor: 'rgba(23, 48, 35, 0.8)',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' Statements';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection

@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6 text-btlGreen">Liste des états financiers</h1>

    <!-- Search and Filter Form -->
    <form id="filterForm" method="GET" action="{{ route('financial-statement.fetch_all') }}" class="mb-6 flex space-x-4">
        <input
            type="text"
            id="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Recherche par nom"
            class="border border-gray-300 rounded-lg px-4 py-2 w-3/4 shadow-sm focus:ring focus:ring-btlRed"
        >
        <input
            type="date"
            id="start_date"
            name="start_date"
            value="{{ request('start_date') }}"
            placeholder="Start Date"
            class="border border-gray-300 rounded-lg px-4 py-2 w-1/8 shadow-sm focus:ring focus:ring-btlRed"
        >
        <input
            type="date"
            id="end_date"
            name="end_date"
            value="{{ request('end_date') }}"
            placeholder="End Date"
            class="border border-gray-300 rounded-lg px-4 py-2 w-1/8 shadow-sm focus:ring focus:ring-btlRed"
        >
    </form>

    <!-- Table -->
    <div id="results" class="overflow-x-auto bg-white rounded-lg shadow-md">
        @include('financial-statement.fetch-all.partials.table', ['financialStatements' => $financialStatements])
    </div>

    <!-- Pagination -->
    <div id="pagination" class="mt-4">
        {{ $financialStatements->links() }}
    </div>
</div>
@endsection

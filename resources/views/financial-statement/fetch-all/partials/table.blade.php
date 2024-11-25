<table class="w-full border-collapse bg-white rounded-lg shadow">
    <thead class="bg-btlGreen text-white">
        <tr>
            <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Company Name</th>
            <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Statement Date</th>
            <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Document</th>
            <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Consult</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
        @forelse($financialStatements as $statement)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-gray-700">{{ $statement->company->name }}</td>
                <td class="px-6 py-4 text-gray-700">{{ $statement->date }}</td>
                <td class="px-6 py-4">
                    <a href="{{ asset($statement->file_path) }}" target="_blank" class="text-btlRed underline hover:text-red-600">
                        Download
                    </a>
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('financial-statement.show', ['id' => $statement->id, 'date' => $statement->date]) }}" 
                       class="text-btlRed underline hover:text-red-600">
                        Consult
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center py-4 text-gray-500">No financial statements found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

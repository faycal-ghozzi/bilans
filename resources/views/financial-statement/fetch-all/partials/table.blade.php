<table class="w-full border-collapse bg-white rounded-lg shadow">
    <thead class="bg-btlGreen text-white">
        <tr>
            <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Dénomination sociale</th>
            <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Date État Financier</th>
            <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Document attaché</th>
            <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Détails</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
        @forelse($financialStatements as $statement)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-gray-700">{{ $statement->company->name }}</td>
                <td class="px-6 py-4 text-gray-700">{{ $statement->date }}</td>
                <td class="px-6 py-4">
                    <a href="{{ asset($statement->file_path) }}" target="_blank" class="text-btlRed underline hover:text-red-600">
                        Télecharger
                    </a>
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('financial-statement.show', ['id' => $statement->id, 'date' => $statement->date]) }}" 
                       class="text-btlRed underline hover:text-red-600">
                        Consulter
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center py-4 text-gray-500">Aucun état financier trouvé.</td>
            </tr>
        @endforelse
    </tbody>
</table>

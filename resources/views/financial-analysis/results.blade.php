@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar', ['id' => $file->company, 'date' => $file->date])
@endsection

@section('content')
<div class="container mx-auto p-8">
    <div class="bg-white p-8 shadow-md rounded-lg">
        <h1 class="text-2xl font-bold mb-6 text-center">Analyse Financière</h1>

        <table class="w-full border-collapse bg-white rounded-lg shadow">
            <thead class="bg-btlGreen text-white">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Catégorie</th>
                    <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Ratio</th>
                    <th class="px-6 py-3 text-left font-semibold text-sm uppercase">N</th>
                    <th class="px-6 py-3 text-left font-semibold text-sm uppercase">N-1</th>
                    <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Évolution Absolue</th>
                    <th class="px-6 py-3 text-left font-semibold text-sm uppercase">Évolution %</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ratios as $categorie => $categorieRatios)
                    <tr class="bg-gray-200">
                        <td colspan="6" class="font-bold px-6 py-4">{{ ucfirst($categorie) }}</td>
                    </tr>
                    @foreach ($categorieRatios as $nom => $valeurs)
                        <tr class="hover:bg-gray-100 hover:scale-105 transition-transform duration-150 ease-in-out">
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4">{{ $nom }}</td>
                            <td class="px-6 py-4">
                                {{ $valeurs['valeur'] !== null ? (strpos($nom, '%') !== false ? number_format($valeurs['valeur'] * 100, 2) . '%' : number_format($valeurs['valeur'], 2)) : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $valeurs['n_1'] !== null ? (strpos($nom, '%') !== false ? number_format($valeurs['n_1'] * 100, 2) . '%' : number_format($valeurs['n_1'], 2)) : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $valeurs['absolue'] !== null ? number_format($valeurs['absolue'], 2) : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $valeurs['pourcentage'] !== null ? number_format($valeurs['pourcentage'], 2) . '%' : '-' }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

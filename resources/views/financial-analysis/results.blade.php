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
                    <tr>
                        <td colspan="6" class="font-bold px-6 py-4 bg-gray-200">{{ ucfirst($categorie) }}</td>
                    </tr>
                    @foreach ($categorieRatios as $nom => $valeurs)
                        <tr class="hover:scale-105 transition-transform duration-150 ease-in-out">
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4">{{ $nom }}</td>
                            <td class="px-6 py-4">
                                {{ number_format($valeurs['valeur_n'], 2) }} {{ strpos($nom, '%') !== false ? '%' : '' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ number_format($valeurs['valeur_n_1'], 2) }} {{ strpos($nom, '%') !== false ? '%' : '' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ number_format($valeurs['evolution_absolue'], 2) }}
                            </td>
                            <td class="px-6 py-4">
                                {{ number_format($valeurs['evolution_pourcentage'], 2) }}%
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

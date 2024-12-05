@extends('layouts.app')

@section('title', 'Formulaire Bilan Comptable')

@section('content')
    <div class="bg-white p-8 shadow-md rounded-lg ">
        <h2 class="text-2xl font-bold mb-8 text-center col-secondary">
            Insérer États Financiers
        </h2>

        @if(session('success'))
            <div class="p-4 mb-4 text-green-800 bg-green-200 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form id="financial-form" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @include('financial-statement.wizard.step1')
            @include('financial-statement.wizard.step2')
            @include('financial-statement.wizard.step3')
            @include('financial-statement.wizard.step4')
            @include('financial-statement.wizard.step5')
        </form>
    </div>
@endsection
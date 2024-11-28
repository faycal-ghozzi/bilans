@php
    $previousCategory = null;
    $firstEntry = true;
@endphp

<h3 class="text-2xl font-bold mb-6">Capitaux Propres & Passifs</h3>
<section>
    <div class="grid grid-cols-1 gap-y-8">

    @foreach($capitaux as $capital)
        @if($capital->category === "Capitaux Propres et Passifs")
            @php
                break;
            @endphp
        @endif
        @if ($previousCategory !== $capital->category)
            <div class="grid grid-cols-3 items-center gap-x-4">
                <h3 class="text-lg font-semibold mt-8">{{ $capital->category }}</h3>
                @if($firstEntry)
                    <h3 class="text-lg font-semibold mt-8 flex items-center justify-center">n</h3>
                    <h3 class="text-lg font-semibold mt-8 flex items-center justify-center">n-1</h3>
                @endif
            </div>
            @if ($previousCategory === null)
                <h4 class="font-medium text-gray-600">Capitaux Propres</h4>
            @endif
            @php
                $firstEntry = false;
                $previousCategory = $capital->category;
            @endphp
        @endif


        <div class="grid grid-cols-3 items-center gap-x-4" data-decoration="{{ $capital->decoration }}">
            <label 
                class="{{ $capital->decoration == 'stripe' ? 'font-bold text-white' : 'text-gray-700 font-medium'}} {{ $capital->decoration == 'bold' ? 'text-black font-bold' : 'text-gray-700 font-medium'}}">{{ $capital->label }}</label>
            
            <input type="text" name="capitaux[{{ $capital->id }}][n]"
                id="capitaux_{{ strtolower(str_replace([' ', '\'', '(', ')', '/'], ['_', '', '', '', '_'], $capital->label)) }}_n"
                placeholder=""
                class="px-4 py-2 border rounded-md w-full text-right number"
                {{ str_contains(strtolower($capital->label), 'total') ? 'disabled' : ''}}
                data-role="{{ $capital->role }}"
                data-year="n"
                />
            <input type="text" name="capitaux[{{ $capital->id }}][n-1]"
                id="capitaux_{{ strtolower(str_replace([' ', '\'', '(', ')', '/'], ['_', '', '', '', '_'], $capital->label)) }}_n-1"
                placeholder=""
                class="px-4 py-2 border rounded-md w-full text-right number"
                {{ str_contains(strtolower($capital->label), 'total') ? 'disabled' : ''}}
                data-role="{{ $capital->role }}"
                data-year="n-1"
                />
        </div>
    @endforeach
    
    @foreach($passifs as $passif)
        @if ($previousCategory !== $passif->category)
            <h3 class="text-lg font-semibold mt-8">{{ $passif->category }}</h3>
            @if ($previousCategory === null)
                <h4 class="font-medium text-gray-600">Passifs</h4>
            @endif
            @php
                $previousCategory = $passif->category;
            @endphp
        @endif

        <div class="grid grid-cols-3 items-center gap-x-4" data-decoration="{{ $passif->decoration }}">
            <label class="{{ $passif->decoration == 'stripe' ? 'font-bold text-white' : 'text-gray-700 font-medium'}} {{ $passif->decoration == 'bold' ? 'text-black font-bold' : 'text-gray-700 font-medium'}}">{{ $passif->label }}</label>
            
            <input type="text" name="passifs[{{ $passif->id }}][n]"
                id="passifs_{{ strtolower(str_replace([' ', '\'', '(', ')', '/'], ['_', '', '', '', '_'], $passif->label)) }}_n"
                placeholder=""
                class="px-4 py-2 border rounded-md w-full text-right number"
                {{ str_contains(strtolower($passif->label), 'total') ? 'disabled' : ''}}
                data-role="{{ $passif->role }}"
                data-year="n"
                />
            <input type="text" name="passifs[{{ $passif->id }}][n-1]"
                id="passifs_{{ strtolower(str_replace([' ', '\'', '(', ')', '/'], ['_', '', '', '', '_'], $passif->label)) }}_n-1"
                placeholder=""
                class="px-4 py-2 border rounded-md w-full text-right number"
                {{ str_contains(strtolower($passif->label), 'total') ? 'disabled' : ''}}
                data-role="{{ $passif->role }}"
                data-year="n-1"
                />
        </div>
    @endforeach
    </div>
</section>

<h3 class="text-2xl font-bold mb-6">Informations</h3>
<section>
    <div class="grid grid-cols-1 gap-y-8 mt-20">
        <div class="grid grid-cols-4 items-center gap-x-4">
            <label for="company_name" class="font-medium text-gray-700 col-start-2">Dénomination Sociale</label>
            <input type="text" name="company_name" id="company_name" class="form_control px-4 py-2 border bg-gray-100 rounded-md w-full text-right col-start-3 step-1-verif" required>
        </div>

        <div class="grid grid-cols-4 items-center gap-x-4">
            <label for="company_year" class="font-medium text-gray-700 col-start-2">Date des États Financiers</label>
            <input type="date" name="current_year" id="current_year" class="form_control px-4 py-2 border bg-gray-100 rounded-md w-full text-right col-start-3 step-1-verif" required>
        </div>

        <div class="grid grid-cols-3 items-center gap-x-4">
            <p id="error-message-step-1" class="text-red-500 mt-2 hidden col-start-2">Veuillez remplir les champs avant de continuer</p>
        </div>

    </div>
</section>
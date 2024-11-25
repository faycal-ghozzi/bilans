export function formatNumber(number) {
    return number.toLocaleString('en', { minimumFractionDigits: 3, maximumFractionDigits: 3 }).replace(/,/g, ' ');
}

export function cleanNumber(value) {
    return (value || '').replace(/ /g, '');
}

export function formatInputs() {
    const allowedIds = [
        "resultats_variation_des_stocks_des_produits_finis_et_des_encours_n-1", 
        "resultats_variation_des_stocks_des_produits_finis_et_des_encours_n", 
        "resultats_eléments_extraordinaires_gains_pertes_n-1", 
        "resultats_eléments_extraordinaires_gains_pertes_n", 
        "actifs_total_actifs_immobilisés_n-1", 
        "actifs_total_actifs_immobilisés_n", 
        "actifs_total_des_actifs_non_courants_n-1", 
        "actifs_total_des_actifs_non_courants_n",
        "actifs_total_des_actifs_courants_n-1",
        "actifs_total_des_actifs_courants_n",
        "actifs_total_des_actifs_n-1",
        "actifs_total_des_actifs_n"];
    $('.number').on('input', function() {
        const $input = $(this);
        let inputValue = $input.val();

        const allowNegative = allowedIds.includes($input.attr("id"));

        const regex = allowNegative ? /^-?\d*[.,]?\d*$/ :/^\d*[.,]?\d*$/;

        if(!regex.test(inputValue)){
            $input.val($input.data("previous") || "");
        }

        const pointCount = (inputValue.match(/\./g) || []).length;
        const commaCount = (inputValue.match(/,/g) || []).length;

        if(pointCount > 1 || commaCount > 1 || (pointCount === 1 && commaCount ===1)) {
            $input.val($input.data("previous") || "");
            return
        }
        $input.data("previous", inputValue);
    });
}
import { formatNumber, cleanNumber, formatInputs } from './utils';

$(document).ready(function() {
    calcProduitsExploitation();
    calcChargesExploitation();
    calcCharges();
    calcDivers();
    formatInputs();
})

function calcProduitsExploitation(){
    $('[data-role="Produits exploitation"]').on('blur', function() {
        let values_n_1 = $('[data-role="Produits exploitation"][data-year="n-1"]').map(function() {
            return parseFloat($(this).val().replace(",", ".")) || 0;
        }).get()

        let values_n = $('[data-role="Produits exploitation"][data-year="n"]').map(function() {
            return parseFloat($(this).val().replace(",", ".")) || 0;
        }).get()
        let sum_n_1 = values_n_1.reduce((a, b) => a + b, 0)
        let sum_n = values_n.reduce((a, b) => a + b, 0)

        $('#resultats_total_produits_dexploitation_n-1').val(formatNumber(sum_n_1));
        $('#resultats_total_produits_dexploitation_n').val(formatNumber(sum_n));
    })
}

function calcChargesExploitation(){
    $('[data-role="Charges exploitation"]').on('blur', function() {
        let values_n_1 = $('[data-role="Charges exploitation"][data-year="n-1"]').map(function() {
            return parseFloat($(this).val().replace(",", ".")) || 0;
        }).get()

        let values_n = $('[data-role="Charges exploitation"][data-year="n"]').map(function() {
            return parseFloat($(this).val().replace(",", ".")) || 0;
        }).get()

        let sum_n_1 = values_n_1.reduce((a, b) => a + b, 0)
        let sum_n = values_n.reduce((a, b) => a + b, 0)

        $('#resultats_total_charges_dexploitation_n-1').val(formatNumber(sum_n_1));
        $('#resultats_total_charges_dexploitation_n').val(formatNumber(sum_n));
    })
}

function calcCharges(){
    $('[data-role^="Activites ordinaires"]').on('blur', function(){
        let values_n_1 = $('[data-role^="Activites ordinaires"][data-year="n-1"]').map(function() {
            let value = parseFloat($(this).val().replace(",", ".")) || 0;
            return $(this).data('role').includes('gains') ? -value : value; 
        }).get();

        let values_n = $('[data-role^="Activites ordinaires"][data-year="n"]').map(function() {
            let value = parseFloat($(this).val().replace(",", ".")) || 0;
            return $(this).data('role').includes('gains') ? -value : value; 
        }).get();

        
        let sum_n_1 = values_n_1.reduce((a, b) => a + b, 0);
        let sum_n = values_n.reduce((a, b) => a + b, 0);

        let resultats_activites_ordinaires_n_1 = parseFloat(cleanNumber($('#resultats_résultat_dexploitation__n-1').val().replace(",", "."))) || 0;
        let resultats_résultat_des_activités_ordinaires_avant_impôt_n_1 =  resultats_activites_ordinaires_n_1 - (sum_n_1-values_n_1.at(-1));
        let resultats_activites_ordinaires_n = parseFloat(cleanNumber($('#resultats_résultat_dexploitation__n').val().replace(",", "."))) || 0;
        let resultats_résultat_des_activités_ordinaires_avant_impôt_n = resultats_activites_ordinaires_n - (sum_n-values_n.at(-1));


        $('#resultats_résultat_des_activités_ordinaires_avant_impôt_n-1').val(formatNumber(resultats_résultat_des_activités_ordinaires_avant_impôt_n_1));
        $('#resultats_résultat_des_activités_ordinaires_avant_impôt_n').val(formatNumber(resultats_résultat_des_activités_ordinaires_avant_impôt_n));  
    })
}

function calcDivers(){
    $('[data-role^="Activites ordinaires"], [data-role="Charges exploitation"], [data-role="Produits exploitation"], [data-role="Impots"], [data-role^="Elements extraordinaires"], [data-role="Modifications comptables"]').on('blur', function(){

        let total_produits_exploitation_n_1 = parseFloat(cleanNumber($('#resultats_total_produits_dexploitation_n-1').val().replace(",", "."))) || 0
        let total_charges_exploitation_n_1 = parseFloat(cleanNumber($('#resultats_total_charges_dexploitation_n-1').val().replace(",", "."))) || 0

        let resultats_exploitation_n_1 = total_produits_exploitation_n_1 - total_charges_exploitation_n_1;
        $('#resultats_résultat_dexploitation__n-1').val(formatNumber(resultats_exploitation_n_1));

        let total_produits_exploitation_n = parseFloat(cleanNumber($('#resultats_total_produits_dexploitation_n').val().replace(",", "."))) || 0
        let total_charges_exploitation_n = parseFloat(cleanNumber($('#resultats_total_charges_dexploitation_n').val().replace(",", "."))) || 0

        let resultats_exploitation_n = total_produits_exploitation_n - total_charges_exploitation_n;

        $('#resultats_résultat_dexploitation__n').val(formatNumber(resultats_exploitation_n));

        let impots_sur_benefices_n_1 = parseFloat(cleanNumber($('#resultats_impôt_sur_les_bénéfices_n-1').val().replace(",", "."))) || 0
        let impots_sur_benefices_n = parseFloat(cleanNumber($('#resultats_impôt_sur_les_bénéfices_n').val().replace(",", "."))) || 0

        let résultat_des_activités_ordinaires_avant_impôt_n_1 = parseFloat(cleanNumber($('#resultats_résultat_des_activités_ordinaires_avant_impôt_n-1').val().replace(",", "."))) || 0
        let résultat_des_activités_ordinaires_avant_impôt_n = parseFloat(cleanNumber($('#resultats_résultat_des_activités_ordinaires_avant_impôt_n').val().replace(",", "."))) || 0

        let résultat_des_activités_ordinaires_après_impôt_n_1 = résultat_des_activités_ordinaires_avant_impôt_n_1 - impots_sur_benefices_n_1
        let résultat_des_activités_ordinaires_après_impôt_n = résultat_des_activités_ordinaires_avant_impôt_n - impots_sur_benefices_n


        $('#resultats_résultat_des_activités_ordinaires_après_impôt_n-1').val(formatNumber(résultat_des_activités_ordinaires_après_impôt_n_1))
        $('#resultats_résultat_des_activités_ordinaires_après_impôt_n').val(formatNumber(résultat_des_activités_ordinaires_après_impôt_n))

        let eléments_extraordinaires_gains_pertes_n_1 = parseFloat(cleanNumber($('#resultats_eléments_extraordinaires_gains_pertes_n-1').val().replace(",", "."))) || 0
        let eléments_extraordinaires_gains_pertes_n = parseFloat(cleanNumber($('#resultats_eléments_extraordinaires_gains_pertes_n').val().replace(",", "."))) || 0

        let résultat_net_de_lexercice_n_1 = résultat_des_activités_ordinaires_après_impôt_n_1 + eléments_extraordinaires_gains_pertes_n_1;
        let résultat_net_de_lexercice_n = résultat_des_activités_ordinaires_après_impôt_n + eléments_extraordinaires_gains_pertes_n;

        $('#resultats_résultat_net_de_lexercice_n-1').val(formatNumber(résultat_net_de_lexercice_n_1));
        $('#resultats_résultat_net_de_lexercice_n').val(formatNumber(résultat_net_de_lexercice_n));

        let effet_modifications_comptables_n_1 = parseFloat(cleanNumber($('#resultats_effet_des_modifications_comptables_n-1').val().replace(",", "."))) || 0
        let effet_modifications_comptables_n = parseFloat(cleanNumber($('#resultats_effet_des_modifications_comptables_n').val().replace(",", "."))) || 0

        let resultat_apres_modification_comptable_n_1 = résultat_net_de_lexercice_n_1 - effet_modifications_comptables_n_1
        let resultat_apres_modification_comptable_n = résultat_net_de_lexercice_n - effet_modifications_comptables_n


        $('#resultats_résultat_après_modifications_comptables_n-1').val(formatNumber(resultat_apres_modification_comptable_n_1))
        $('#resultats_résultat_après_modifications_comptables_n').val(formatNumber(resultat_apres_modification_comptable_n))
    })
}
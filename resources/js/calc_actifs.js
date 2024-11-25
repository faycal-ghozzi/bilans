import { formatNumber, cleanNumber, formatInputs } from './utils';

$(document).ready(function() {
    calcImmobilisations('incorporelles')
    calcImmobilisations('corporelles')
    calcImmobilisations('financières')
    calcActifsImmobilises();
    calcANC();
    calcAC();
    calcActifs();
    formatInputs();
});

function calcImmobilisations(type){
    $('[data-role^="Actifs Immobilises"]').on('blur', function(){
        let values_n_1 = $(`[data-role^="Actifs Immobilises"][data-type="${type}"][data-year="n-1"]`).map(function(){
            let value = parseFloat($(this).val().replace(",", ".")) || 0;
            return $(this).data('role').includes('amortissement') || $(this).data('role').includes('provision') ? -value : value;
        }).get()
    
        let values_n = $(`[data-role^="Actifs Immobilises"][data-type="${type}"][data-year="n"]`).map(function(){
            let value = parseFloat($(this).val().replace(",", ".")) || 0;
            return $(this).data('role').includes('amortissement') || $(this).data('role').includes('provision') ? -value : value;
        }).get()
    
        let sum_n_1 = values_n_1.reduce((a, b) => a + b, 0)
        let sum_n = values_n.reduce((a, b) => a + b, 0)
    
        switch (type){
            case "incorporelles" :
                $('#actifs_amortissements_-_immobilisations_incorporelles_n-1_result').val(formatNumber(sum_n_1));
                $('#actifs_amortissements_-_immobilisations_incorporelles_n_result').val(formatNumber(sum_n));
                break;
            case "corporelles" :
                $('#actifs_amortissements_-_immobilisations_corporelles_n-1_result').val(formatNumber(sum_n_1));
                $('#actifs_amortissements_-_immobilisations_corporelles_n_result').val(formatNumber(sum_n));
                break;
            case "financières" :
                $('#actifs_provisions_-_immobilisations_financières_n-1_result').val(formatNumber(sum_n_1));
                $('#actifs_provisions_-_immobilisations_financières_n_result').val(formatNumber(sum_n));
                break;
        }
    })
}

function calcActifsImmobilises(){
    $('[data-role^="Actifs Immobilises"]').on('blur', function() {

        const immobilisations_incorporelles_n_1 = parseFloat(cleanNumber($('#actifs_amortissements_-_immobilisations_incorporelles_n-1_result').val().replace(",", "."))) || 0;
        const immobilisations_corporelles_n_1 = parseFloat(cleanNumber($('#actifs_amortissements_-_immobilisations_corporelles_n-1_result').val().replace(",", "."))) || 0;
        const immobilisations_financieres_n_1 = parseFloat(cleanNumber($('#actifs_provisions_-_immobilisations_financières_n-1_result').val().replace(",", "."))) || 0;

        const totalActifsImmobilises_n_1 = immobilisations_incorporelles_n_1 + immobilisations_corporelles_n_1 + immobilisations_financieres_n_1;

        $('#actifs_total_actifs_immobilisés_n-1').val(formatNumber(totalActifsImmobilises_n_1))

        const immobilisations_incorporelles_n = parseFloat(cleanNumber($('#actifs_amortissements_-_immobilisations_incorporelles_n_result').val().replace(",", "."))) || 0;
        const immobilisations_corporelles_n = parseFloat(cleanNumber($('#actifs_amortissements_-_immobilisations_corporelles_n_result').val().replace(",", "."))) || 0;
        const immobilisations_financieres_n = parseFloat(cleanNumber($('#actifs_provisions_-_immobilisations_financières_n_result').val().replace(",", "."))) || 0;

        const totalActifsImmobilises_n = immobilisations_incorporelles_n + immobilisations_corporelles_n + immobilisations_financieres_n;

        $('#actifs_total_actifs_immobilisés_n').val(formatNumber(totalActifsImmobilises_n))
    })
}

function calcAutreANC(){
    $('[data-role="Actif non courant"]').on('blur', function() {
        let values_n_1 = $('[data-role="Actif non courant"][data-year="n-1"]').map(function() {
            return parseFloat($(this).val().replace(",", ".")) || 0;
        }).get()

        let values_n = $('[data-role="Actif non courant"][data-year="n"]').map(function() {
            return parseFloat($(this).val().replace(",", ".")) || 0;
        }).get()

        let sum_n_1 = values_n_1.reduce((a, b) => a + b, 0)
        let sum_n = values_n.reduce((a, b) => a + b, 0)

        $('#actifs_total_autre_actifs_non_courants_n-1').val(formatNumber(sum_n_1));
        $('#actifs_total_autre_actifs_non_courants_n').val(formatNumber(sum_n));
    })
}

function calcANC(){
    $('[data-role="Actif non courant"],[data-role^="Actifs Immobilises"]').on('blur', function(){

        const actifs_total_actifs_immobilisés_n_1 = parseFloat(cleanNumber($('#actifs_total_actifs_immobilisés_n-1').val().replace(",", "."))) || 0;
        const actifs_autres_actifs_non_courants_n_1 = parseFloat(cleanNumber($('#actifs_autres_actifs_non_courants_n-1').val().replace(",", "."))) || 0;

        const total_anc_n_1 = actifs_total_actifs_immobilisés_n_1 + actifs_autres_actifs_non_courants_n_1;

        const actifs_total_actifs_immobilisés_n = parseFloat(cleanNumber($('#actifs_total_actifs_immobilisés_n').val().replace(",", "."))) || 0;
        const actifs_autres_actifs_non_courants_n = parseFloat(cleanNumber($('#actifs_autres_actifs_non_courants_n').val().replace(",", "."))) || 0;

        const total_anc_n = actifs_total_actifs_immobilisés_n + actifs_autres_actifs_non_courants_n;

        $('#actifs_total_des_actifs_non_courants_n-1').val(formatNumber(total_anc_n_1));
        $('#actifs_total_des_actifs_non_courants_n').val(formatNumber(total_anc_n));
    })
}

function calcAC(){

    $('[data-role="Actif Courant"], [data-role="Actif Courant - provision"]').on('blur', function(){
        let values_n_1 = $('[data-role^="Actif Courant"][data-year="n-1"]').map(function() {
            let value = parseFloat($(this).val().replace(",", ".")) || 0;
            return $(this).data('role').includes('provision') ? -value : value; 
        }).get()

        let values_n = $('[data-role^="Actif Courant"][data-year="n"]').map(function() {
            let value = parseFloat($(this).val().replace(",", ".")) || 0;
            return $(this).data('role').includes('provision') ? -value : value; 
        }).get()

        let sum_n_1 = values_n_1.reduce((a, b) => a + b, 0)
        let sum_n = values_n.reduce((a, b) => a + b, 0)

        const stocks_n_1 = parseFloat(cleanNumber($('#actifs_stocks_n-1').val().replace(",", "."))) || 0;
        const provisions_stocks_n_1 = parseFloat(cleanNumber($('#actifs_provisions_-_stocks_n-1').val().replace(",", "."))) || 0;
        const client_et_comptes_n_1 = parseFloat(cleanNumber($('#actifs_clients_et_comptes_rattachés_n-1').val().replace(",", "."))) || 0;
        const provisions_client_et_comptes_n_1 = parseFloat(cleanNumber($('#actifs_provisions_-_clients_et_comptes_rattachés_n-1').val().replace(",", "."))) || 0;

        const total_stock_n_1 = stocks_n_1 - provisions_stocks_n_1;
        const total_clients_n_1 = client_et_comptes_n_1 - provisions_client_et_comptes_n_1;

        $('#actifs_provisions_-_stocks_n-1_result').val(formatNumber(total_stock_n_1));
        $('#actifs_provisions_-_clients_et_comptes_rattachés_n-1_result').val(formatNumber(total_clients_n_1));

        const stocks_n = parseFloat(cleanNumber($('#actifs_stocks_n').val().replace(",", "."))) || 0;
        const provisions_stocks_n = parseFloat(cleanNumber($('#actifs_provisions_-_stocks_n').val().replace(",", "."))) || 0;
        const client_et_comptes_n = parseFloat(cleanNumber($('#actifs_clients_et_comptes_rattachés_n').val().replace(",", "."))) || 0;
        const provisions_client_et_comptes_n = parseFloat(cleanNumber($('#actifs_provisions_-_clients_et_comptes_rattachés_n').val().replace(",", "."))) || 0;

        const total_stock_n = stocks_n - provisions_stocks_n;
        const total_clients_n = client_et_comptes_n - provisions_client_et_comptes_n;

        $('#actifs_provisions_-_stocks_n_result').val(formatNumber(total_stock_n));
        $('#actifs_provisions_-_clients_et_comptes_rattachés_n_result').val(formatNumber(total_clients_n));

        $('#actifs_total_des_actifs_courants_n-1').val(formatNumber(sum_n_1-values_n_1.at(-1)));
        $('#actifs_total_des_actifs_courants_n').val(formatNumber(sum_n-values_n.at(-1)));
    })
}

function calcActifs(){
    $('[data-role^="Actif"]').on('blur', function(){
        const total_des_actifs_non_courants_n_1 = parseFloat(cleanNumber($('#actifs_total_des_actifs_non_courants_n-1').val().replace(",", "."))) || 0;
        const total_des_actifs_courants_n_1 = parseFloat(cleanNumber($('#actifs_total_des_actifs_courants_n-1').val().replace(",", "."))) || 0;
    
        const total_des_actifs_non_courants_n = parseFloat(cleanNumber($('#actifs_total_des_actifs_non_courants_n').val().replace(",", "."))) || 0;
        const total_des_actifs_courants_n = parseFloat(cleanNumber($('#actifs_total_des_actifs_courants_n').val().replace(",", "."))) || 0;
    
        const total_actifs_n_1 = total_des_actifs_courants_n_1 + total_des_actifs_non_courants_n_1;
        $('#actifs_total_des_actifs_n-1').val(formatNumber(total_actifs_n_1))
    
        const total_actifs_n = total_des_actifs_courants_n + total_des_actifs_non_courants_n;
        $('#actifs_total_des_actifs_n').val(formatNumber(total_actifs_n))
    })
}


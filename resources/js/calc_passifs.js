import { formatNumber, cleanNumber, formatInputs } from './utils';

$(document).ready(function(){
    calcCP();
    calcPNC();
    calcPC();
    calcCPetP();
    formatInputs();
})

function calcCP(){
    $('[data-role="Capitaux propres"]').on('blur', function(){
        let values_n_1 = $('[data-role="Capitaux propres"][data-year="n-1"]').map(function(){
            return parseFloat($(this).val().replace(",", ".")) || 0;
        }).get()
    
        let values_n = $('[data-role="Capitaux propres"][data-year="n"]').map(function(){
            return parseFloat($(this).val().replace(",", ".")) || 0;
        }).get()
    
        let sum_n_1 = values_n_1.reduce((a, b) => a + b, 0)
        let sum_n = values_n.reduce((a, b) => a + b, 0)

        const resultats_exercice_n_1 = parseFloat(cleanNumber($('#capitaux_résultat_de_lexercice_n-1').val())) || 0;
        const resultats_exercice_n = parseFloat(cleanNumber($('#capitaux_résultat_de_lexercice_n').val())) || 0;

        $('#capitaux_total_des_capitaux_propres_avant_résultat_de_lexercice_n-1').val(formatNumber(sum_n_1 - resultats_exercice_n_1));
        $('#capitaux_total_des_capitaux_propres_avant_résultat_de_lexercice_n').val(formatNumber(sum_n - resultats_exercice_n));

        $('#capitaux_total_des_capitaux_propres_après_résultat_de_lexercice_n-1').val(formatNumber(sum_n_1));
        $('#capitaux_total_des_capitaux_propres_après_résultat_de_lexercice_n').val(formatNumber(sum_n));
    })
}

function calcPNC(){
    $('[data-role="Passif non courant"]').on('blur', function(){
        let values_n_1 = $('[data-role="Passif non courant"][data-year="n-1"]').map(function(){
            return parseFloat($(this).val().replace(",", ".")) || 0;
        }).get()
    
        let values_n = $('[data-role="Passif non courant"][data-year="n"]').map(function(){
            return parseFloat($(this).val().replace(",", ".")) || 0;
        }).get()
    
        let sum_n_1 = values_n_1.reduce((a, b) => a + b, 0)
        let sum_n = values_n.reduce((a, b) => a + b, 0)

        $('#passifs_total_des_passifs_non_courants_n-1').val(formatNumber(sum_n_1));
        $('#passifs_total_des_passifs_non_courants_n').val(formatNumber(sum_n));
    })
}

function calcPC(){
    $('[data-role="Passif courant"]').on('blur', function(){
        let values_n_1 = $('[data-role="Passif courant"][data-year="n-1"]').map(function(){
            return parseFloat($(this).val().replace(",", ".")) || 0;
        }).get()
    
        let values_n = $('[data-role="Passif courant"][data-year="n"]').map(function(){
            return parseFloat($(this).val().replace(",", ".")) || 0;
        }).get()
    
        let sum_n_1 = values_n_1.reduce((a, b) => a + b, 0)
        let sum_n = values_n.reduce((a, b) => a + b, 0)

        $('#passifs_total_des_passifs_courants_n-1').val(formatNumber(sum_n_1));
        $('#passifs_total_des_passifs_courants_n').val(formatNumber(sum_n));
    })
}

function calcCPetP(){
    $('[data-role="Capitaux propres"], [data-role^="Passif"]').on('blur', function(){
        const total_cp_n_1 = parseFloat(cleanNumber($('#capitaux_total_des_capitaux_propres_après_résultat_de_lexercice_n-1').val().replace(",", "."))) || 0;
        const total_cp_n = parseFloat(cleanNumber($('#capitaux_total_des_capitaux_propres_après_résultat_de_lexercice_n').val().replace(",", "."))) || 0;

        const total_pnc_n_1 = parseFloat(cleanNumber($('#passifs_total_des_passifs_non_courants_n-1').val().replace(",", "."))) || 0;
        const total_pnc_n = parseFloat(cleanNumber($('#passifs_total_des_passifs_non_courants_n').val().replace(",", "."))) || 0;

        const total_pc_n_1 = parseFloat(cleanNumber($('#passifs_total_des_passifs_courants_n-1').val().replace(",", "."))) || 0;
        const total_pc_n = parseFloat(cleanNumber($('#passifs_total_des_passifs_courants_n').val().replace(",", "."))) || 0;
        
        const total_passifs_n_1 = total_pnc_n_1 + total_pc_n_1;
        const total_passifs_n = total_pnc_n + total_pc_n;

        const total_cp_p_n_1 = total_cp_n_1 + total_passifs_n_1;
        const total_cp_p_n = total_cp_n + total_passifs_n;

        $('#passifs_total_des_passifs_n-1').val(formatNumber(total_passifs_n_1));
        $('#passifs_total_des_passifs_n').val(formatNumber(total_passifs_n));

        $('#passifs_total_des_capitaux_propres_et_passifs_n-1').val(formatNumber(total_cp_p_n_1))
        $('#passifs_total_des_capitaux_propres_et_passifs_n').val(formatNumber(total_cp_p_n));
    })
}
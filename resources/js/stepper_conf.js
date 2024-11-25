import { formatNumber, cleanNumber, formatInputs } from './utils';

function saveDatatoDB(formData){
    $.ajax({
        type: "POST",
        url: '/fs/store',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function() {
            console.log('success')
        },
        error: function() {
            console.log('fail')
        }
    })
}

$(document).ready(function() {
    $("#financial-form").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "fade",
        autoFocus: true,
        labels: {
            finish: "Valider",
            next: "Suivant >",
            previous: "< Précédent"
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            if (newIndex < currentIndex) {
                return true;
            }
            const total_actifs_n_1 = parseFloat(cleanNumber($('#actifs_total_des_actifs_n-1').val().replace(",", "."))) || 0;
            const total_actifs_n = parseFloat(cleanNumber($('#actifs_total_des_actifs_n').val().replace(",", "."))) || 0;
            
            const total_passifs_n_1 = parseFloat(cleanNumber($('#passifs_total_des_capitaux_propres_et_passifs_n-1').val().replace(",", "."))) || 0;
            const total_passifs_n = parseFloat(cleanNumber($('#passifs_total_des_capitaux_propres_et_passifs_n').val().replace(",", "."))) || 0;
            switch (currentIndex){
                case 0:
                    if(!$('#company_name').val() || !$('#current_year').val()){
                        $('#error-message-step-1').show();
                        return false
                    }
                    break;
                case 2:
                    if(total_actifs_n !== total_passifs_n){
                        alert('veillez verifier le bilan de l\'année courante');
                        return false
                    }
                    else if(total_actifs_n_1 !== total_passifs_n_1){
                        alert('veuillez verifier le bilan de l\'année précedente');
                        return false
                    }
                    break;
                case 4:
                    if(!$('#dropzone-file').val()){
                        $('#error-message').show();
                        return false;
                    }
                    break;
            }

            $('#error-message').hide();
            return true; 
        },
        onFinishing: function (event, currentIndex) {
            if(!$('#file_input').val()){
                $('#error-message').show();
                return false;
            }
            return $("#financial-form").valid();
        },
        onFinished: function (event, currentIndex) {

            $("#financial-form")
                .find(":disabled")
                .each(function () {
                    $(this).data("disabled", true).prop("disabled", false);
                });

            var formData = new FormData($("#financial-form")[0])

            $("#financial-form")
                .find(":disabled[data-disabled]")
                .prop("disabled", true)
                .removeData("disabled");
  saveDatatoDB(formData)
        },
        onInit: function (event, currentIndex) {
            $(".actions a[href='#previous']").hide();
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            if (currentIndex === 0) {
                $(".actions a[href='#previous']").hide();
            } else {
                $(".actions a[href='#previous']").show();
            }
        }
    });
});
// // PWS#9

$(document).ready(function(){

  var master_genres_selector = $('#master_genres\\[\\]');

  if(master_genres_selector.length > 0){
    checkAttoriRequired(); // prima verifica al caricamento
    checkSceneggiatoriRequired(); // PWS#04-23v2
    master_genres_selector.on('change', function (e) {
      checkAttoriRequired(); // e tutte le volte che il valore cambia
      checkSceneggiatoriRequired();
    });
  }

  if($('#compo_alias').length > 0 && $('#compo_nome').length > 0 && $('#compo_cognome').length > 0){
      
      $('#compo_nome').on('keyup', function() {
          if(!$('#compo_alias').attr('data-edited')) $('#compo_alias').val($(this).val() + ' ' + $('#compo_cognome').val());
      });

      $('#compo_cognome').on('keyup', function() {
          if(!$('#compo_alias').attr('data-edited')) $('#compo_alias').val($('#compo_nome').val() + ' ' + $(this).val());
      });

      $('#compo_alias').on('keyup', function() {
          $(this).attr('data-edited',true);
      });
  } // PWS#13-comp
  
});

function checkAttoriRequired(){
  id_animazione = '3';
  id_documentario = '11'; // PWS#9-2
  master_genres_selector = $('#master_genres\\[\\]');
  attori_selector = $('#master_actors');
  attori_label = $('label[for="master_actors"]');

  if($.inArray(id_animazione,master_genres_selector.val()) !== -1 || $.inArray(id_documentario,master_genres_selector.val()) !== -1){ // PWS#9-2
    attori_label.removeClass("required");
    attori_selector.attr("aria-required","false");
  } else{
    attori_label.addClass("required");
    attori_selector.attr("aria-required","true");
  }
}

function checkSceneggiatoriRequired(){
  id_documentario = '11';

  master_genres_selector = $('#master_genres\\[\\]');
  sceneggiatori_selector = $('#master_scriptwriters');
  sceneggiatori_label = $('label[for="master_scriptwriters"]');

  if($.inArray(id_documentario,master_genres_selector.val()) !== -1){
    sceneggiatori_label.removeClass("required");
    sceneggiatori_selector.attr("aria-required","false");
  } else{
    sceneggiatori_label.addClass("required");
    sceneggiatori_selector.attr("aria-required","true");
  }
}

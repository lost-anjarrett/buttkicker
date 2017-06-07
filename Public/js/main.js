$(document).ready(function(){

  // désactive le bouton si aucun personnage n'est choisi
  $("form#add-perso input[name='type']").change(function(){
    if($("form#add-perso button").hasClass("disabled")){
      $("form#add-perso button").removeClass("disabled");
    }
  });

  // résout le problème d'affichage suivant :
  // quand on clique sur le bouton, le texte de celui-ci reste noir
  // pas trouvé comment le régler avec CSS
  $("button").click(function(){
    $(this).blur();
  });

  ////////////////////////// CARACTÉRISTIQUES DU PLAYER ///////////////////////////
  $("select#player-selector").change(function(){
    // On charge les caractéristiques du personnage
    $.ajax({
              type: "POST",
              data: {player : $(this).val()},
              url: "../Model/player.php",
              dataType: 'html',
              success: function(data){
                $('div#player .present-perso').html(data);
              },
              error: function(){
                $('div#player .present-perso').html('<strong>Une erreur est survenue</strong>');
              }
    });
    // on désactive le bouton valider quand il n'y a pas de perso séléctionné
    if ($(this).val() === '') {
      $(this).next().addClass('disabled');
      $(this).next().attr('disabled', 'disabled');
    } else {
      $(this).next().removeClass('disabled');
      $(this).next().removeAttr('disabled');
    }
  });

  ///////////////////////// AJOUT D'UN PERSO //////////////////////////////////
  $('div#add-form').on('click', 'button#add', function(){
    $('div#add-form').load('../../Public/View/add-form.phtml');
  });

  // envoi des infos vers add-perso.php
  // obligé d'utiliser la fonction .on() et de cibler le parent car sinon jQuery ne
  // trouve pas le form#add-perso car il n'est pas présent au chargement de la page
  $('div#add-form').on('submit', 'form#add-perso', function(e){
    e.preventDefault();
    var values = $(this).serialize();
    $.ajax({
              type: "POST",
              data: values,
              url: "../Model/add-perso.php",
              dataType: 'html',
              success: function(data){
                $('select#player-selector').append(data);
                $('div#add-form').html('<button type="button" id="add" class="btn">Ajouter un personnage</button>');
                $('div#add-form').append('<span>Personnage ajouté !</span>');
              },
              error: function(){
                $('div#add-form').html('<strong>Une erreur est survenue</strong>');
                $('div#add-form').append('<button type="button" class="btn">Ajouter un personnage</button>');
              }
    });
  });


  ////////////////// VALIDATION DU PERSONNAGE ET DETERMINATION DE L'ADVERSAIRE /////////
  $("div#player form").submit(function(e){
    e.preventDefault();

    $.ajax({
              type: "POST",
              data: {player : $('select#player-selector option:selected').val()},
              url: "../Model/player.php",
              dataType: 'html',
              success: function(data){
                $('div#player').html('<div class="present-perso">'+data+'</div>');
                $('div#player').prepend('<h2>Vous</h2>');
              },
              error: function(){
                $('div#player .present-perso').html('<strong>Une erreur est survenue</strong>');
              }
    });

    $.ajax({
              type: "POST",
              data: {
                player : $('select#player-selector option:selected').val(),
                adversaire : true
              },
              url: "../Model/player.php",
              dataType: 'html',
              success: function(data){
                $('div#adversaire .present-perso').html(data);
                $('div#adversaire').prepend('<h2>Adversaire</h2>').slideDown(1000);
                $('form#go').show(1000);
              },
              error: function(){
                $('div#adversaire .present-perso').html('<strong>Une erreur est survenue</strong>');
              }
    });
  });

  /////////////////// ENVOI DES INFOS VERS LA PAGE DE COMBAT /////////////////////////
  $('form#go').submit(function(){
    $('form#go #idPlayer').val($('#player input').val());
    $('form#go #idAdversaire').val($('#adversaire input').val());
  });


  /////////////////////////// POUR LE COMBAT ////////////////////////
  /**
   * FONCTIONS
   */

  // une fonction qui détermine qui doit jouer
  function setPlayerId()
  {
    var playerId, adversId;
    if (round%2 == 1) {
      playerId = $('#player input').val();
      adversId = $('#adversaire input').val();
    } else {
      playerId = $('#adversaire input').val();
      adversId = $('#player input').val();
    }
    return [playerId, adversId];
  }
  // une fonction pour afficher le tour
  function setTurn()
  {
    if (round%2 == 1) {
      $('h2#round').text('Tour '+(round+1)+' : à votre adversaire');
    } else {
      $('h2#round').text('Tour '+(round+1)+' : à vous de jouer');
    }
    $('div#player').toggleClass('not-your-turn');
    $('div#adversaire').toggleClass('not-your-turn');
  }
  // une fonction pour déterminer l'affichage
  function setReturnElements()
  {
    var player, advers;
    if (round%2 == 1) {
      player = 'div#player article';
      advers = 'div#adversaire article';
    } else {
      player = 'div#adversaire article';
      advers = 'div#player article';
    }
    return [player, advers];
  }
  // et une fonction pour la mise à jour des infos du joueur/adversaire
  function updateInfos(id, element)
  {
    $.ajax({
              type: "POST",
              data: {player : id},
              url: "../Model/player.php",
              dataType: 'html',
              success: function(data){
                $(element).html(data);
              },
              error: function(){
                $(element).html('<strong>Une erreur est survenue</strong>');
              }
    });
  }
  // fonction pour définir l'action entre les deux joueurs
  function setAction(act, player, advers)
  {
    var values = {
      action : act,
      idPlayer : player,
      idAdversaire : advers
    };
    // envoi des données vers action et en retour texte dans le log
    $.ajax({
              type: "POST",
              data: values,
              url: "../Model/action.php",
              dataType: 'html',
              success: function(data){
                $('div.log').prepend(data);
              },
              error: function(){
                $('div.log').prepend('<strong>Une erreur est survenue</strong><br/>');
              }
    });
  }


  // on initialise le round au chargement de la page
  var round = 0;

  $("button#hit").click(function(){
    round++;
    var players = setPlayerId();
    var returnElements = setReturnElements();

    setAction('hit', players[0], players[1]);

    // envoi des données vers player.php pour la mise à jour des infos de l'adversaire
    updateInfos(players[0], returnElements[0]);
    updateInfos(players[1], returnElements[1]);
    setTurn();
  });

  $("button#heal").click(function(){
    round++;
    var players = setPlayerId();
    var returnElements = setReturnElements();

    setAction('heal', players[0], players[1]);

    // envoi des données vers player.php pour la mise à jour des infos du joueur
    updateInfos(players[0], returnElements[0]);
    updateInfos(players[1], returnElements[1]);
    setTurn();
  });

  // finalement on quitte en faisant une redirection vers quit.php avec en GET
  // les ids des deux joueurs
  $("button#quit").click(function(){
    var players = setPlayerId();
    $(location).attr('href','../Model/quit.php?idPlayer=' + players[0] + '&idAdvers=' + players[1]);
  });

});

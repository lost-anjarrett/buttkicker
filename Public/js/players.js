$(function() {
    var $selector = $('select#player-selector');
    var $player = $('div#player');
    var $advers = $('div#advers');
    var $playerInfo = $player.find('.perso-info');
    var $adversInfo = $advers.find('.perso-info');
    var $addForm = $('#add-form');
    var $goForm = $('form#go');
    var $playerForm = $player.find('form');
    var $playerButton = $playerForm.find('button');
    var idPlayer, idAdvers;
    var errorMsg = '<strong>Une erreur est survenue</strong>';
    var addButton = '<button type="button" id="add" class="btn">Ajouter un personnage</button>';

    ////////////////////////// CARACTÉRISTIQUES DU PLAYER ///////////////////////////
    $selector.change(function() {
        $.post({
            data: {
                player: $(this).val()
            },
            url: "player.php",
            success: function(data) {
                $playerInfo.html(data);
            },
            error: function() {
                $playerInfo.html(errorMsg);
            }
        });

        if ($(this).val() === '') {
            $playerButton.addClass('disabled');
            $playerButton.attr('disabled', 'disabled');
        } else {
            $playerButton.removeClass('disabled');
            $playerButton.removeAttr('disabled');
        }
    });

    ///////////////////////// AJOUT D'UN PERSO //////////////////////////////////
    $addForm.on('click', 'button#add', function() {
        $addForm.load('../../Public/View/add-form.phtml');
    });

    $addForm.on('submit', 'form#add-perso', function(e) {
        e.preventDefault();
        var values = $(this).serialize();
        $.post({
            data: values,
            url: "add-perso.php",
            success: function(data) {
                $selector.append(data);
                $addForm.html(addButton);
                $addForm.append('<span>Personnage ajouté !</span>');
            },
            error: function() {
                $addForm.html(errorMsg);
                $addForm.append(addButton);
            }
        });
    });


    ////////////////// VALIDATION DU PERSONNAGE ET DETERMINATION DE L'ADVERSAIRE /////////
    $playerForm.submit(function(e) {
        e.preventDefault();

        $.post({
            data: {
                player: $selector.find(':selected').val()
            },
            url: "player.php",
            success: function(data) {
                $player.html('<div class="perso-info">' + data + '</div>');
                $player.prepend('<h2>Vous</h2>');
            },
            error: function() {
                $player.html(errorMsg);
            }
        });

        $.post({
            data: {
                player: $selector.find(':selected').val(),
                advers: true
            },
            url: "player.php",
            success: function(data) {
                $adversInfo.append(data);
                $advers.prepend('<h2>Adversaire</h2>').slideDown(1000);
                $goForm.show(1000);
            },
            error: function() {
                $adversInfo.html(errorMsg);
            }
        });

    });

    /////////////////// ENVOI DES INFOS VERS LA PAGE DE COMBAT /////////////////////////
    $goForm.submit(function() {
        idPlayer = $player.find('[name="idPlayer"]').val();
        idAdvers = $advers.find('[name="idPlayer"]').val();
        $goForm.find('#idPlayer').val(idPlayer);
        $goForm.find('#idAdvers').val(idAdvers);
    });


});

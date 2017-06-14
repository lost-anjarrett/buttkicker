$(function() {
    /***** INITIALISATION ******/
    var round = 0;
    var playerId = $('#player input').val();
    var adversId = $('#advers input').val();

    var $player = $('div#player article');
    var $advers = $('div#advers article');
    var $log = $('div.log');
    var $hit = $("button#hit");
    var $heal = $("button#heal");
    var $quit = $("button#quit");

    /**
     * FONCTIONS
     */

    function setPlayers()
    {
        if (round%2 == 1) {
            return [playerId, adversId];
        }
        else {
            return [adversId, playerId];
        }
    }

    function setReturnElements()
    {
        if (round%2 == 1) {
            return [$player, $advers];
        }
        else {
            return [$advers, $player];
        }
    }

    function setTurn()
    {
        var text;
        (round%2 == 1) ? text = 'à votre adversaire' : text = 'à vous de jouer';
        $('h2#round').text('Tour '+(round+1)+' : '+text);
        $('div#player').toggleClass('not-your-turn');
        $('div#advers').toggleClass('not-your-turn');
    }

    function updateInfos(id, element)
    {
        $.post({
            data: {
                player: id
            },
            url: "player.php",
            success: function(data) {
                element.html(data);
            },
            error: function() {
                element.html('<strong>Une erreur est survenue</strong>');
            }
        });
    }

    function goToAction(act, player, advers)
    {
        $.post({
            data: {
                action: act,
                idPlayer: player,
                idAdvers: advers
            },
            url: "action.php",
            success: function(data) {
                $log.prepend(data);
            },
            error: function() {
                $log.prepend('<strong>Une erreur est survenue</strong><br/>');
            }
    });}

    function play(e) {
        round++;
        var action = e.target.id;
        var players = setPlayers();
        var returnElements = setReturnElements();

        goToAction(action, players[0], players[1]);

        updateInfos(players[0], returnElements[0]);
        updateInfos(players[1], returnElements[1]);
        setTurn();
    }

    function quit() {
        var players = setPlayers();
        $(location).attr('href','quit.php?idPlayer=' + players[0] + '&idAdvers=' + players[1]);
    }

    /**
     *  EVENEMENTS
     */

    $hit.click(play);
    $heal.click(play);
    $quit.click(quit);


});

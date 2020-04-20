<?php
//Makes a player give cards to someone else

include '../includes/includes.php';

//Required information
inspect_get(GET_GID,GET_PID,GET_TO,GET_TYPES,GET_NUMBERS);
$gid = $_GET[GET_GID];
$pid = $_GET[GET_PID];
$to = $_GET[GET_TO];
$types = explode(MULTIPLE_VALUES_DELIMITER, $_GET[GET_TYPES]);
$numbers = explode(MULTIPLE_VALUES_DELIMITER, $_GET[GET_NUMBERS]);
$cards_count = count($types);

//Testing gets
//Same amount of types and numbers
return_message_on_cond(
    BAD_REQUEST_CODE,
    NUMBERS_AND_TYPES_NOT_SAME_AMOUNT_ERR,
    ($cards_count !== count($numbers)),
    $cards_count,count($numbers)
);
//Between 1 and 2 cards
return_message_on_cond(
    BAD_REQUEST_CODE,
    NOT_RIGHT_AMOUNT_OF_CARDS_ERR,
    ($cards_count <= 0 || $cards_count > 2),
    $cards_count,1,2
);

//Testing states
//Get save
$game = get_game_value($gid);
$players_count = count($game[PLAYERS]);
//Game exists
return_message_on_cond(
    BAD_REQUEST_CODE,
    GAME_DOESNT_EXIST_ERR,
    ($game === null)
);
//Player belongs to this game
return_message_on_cond(
    BAD_REQUEST_CODE,
    NOT_BELONG_TO_THIS_GAME_ERR,
    (!isset($game[PLAYERS][$pid]) || !$game[PLAYERS][$pid]),
    $pid
);
//Target player belongs to this game
return_message_on_cond(
    BAD_REQUEST_CODE,
    NOT_BELONG_TO_THIS_GAME_ERR,
    (!isset($game[PLAYERS][$to]) || !$game[PLAYERS][$to]),
    $pid
);
//Game round < 2
return_message_on_cond(
    BAD_REQUEST_CODE,
    NO_EXCHANGE_AT_THIS_GAME_COUNT_ERR,
    ($game[GAME_COUNT] < 2),
    $game[GAME_COUNT]
);
//Right game state
return_message_on_cond(
    BAD_REQUEST_CODE,
    WRONG_GAME_STATE_ERR,
    ($game[STATE] !== EXCHANGE),
    $game[STATE],EXCHANGE
);
//Player have enough cards
return_message_on_cond(
    BAD_REQUEST_CODE,
    DONT_HAVE_THIS_AMOUNT_OF_CARDS_ERR,
    (count($game[PLAYERS][$pid][CARDS]) < $cards_count),
    count($game[PLAYERS][$pid][CARDS]),$cards_count
);
//Player is neutral
return_message_on_cond(
    BAD_REQUEST_CODE,
    NEUTRAL_DONT_GIVE_CARD_ERR,
    (rank_compare($game[PLAYERS][$pid][PREVIOUS_RANK],NEUTRAL,$game[PREVIOUS_PLAYERS_COUNT]))
);
//Already given
return_message_on_cond(
    BAD_REQUEST_CODE,
    PLAYER_ALREADY_GIVEN_ERR,
    ($game[PLAYERS][$pid][GIVES])
);
//Must give 2 cards if president or asshole
return_message_on_cond(
    BAD_REQUEST_CODE,
    MUST_GIVE_2_CARDS_ERR,
    ((rank_compare($game[PLAYERS][$pid][PREVIOUS_RANK],PRESIDENT,$game[PREVIOUS_PLAYERS_COUNT])
            || rank_compare($game[PLAYERS][$pid][PREVIOUS_RANK],ASSHOLE,$game[PREVIOUS_PLAYERS_COUNT])) && $cards_count !== 2)
);
//Must give 1 card if vice president or vice asshole
return_message_on_cond(
    BAD_REQUEST_CODE,
    MUST_GIVE_1_CARD_ERR,
    ((rank_compare($game[PLAYERS][$pid][PREVIOUS_RANK],VICE_PRESIDENT,$game[PREVIOUS_PLAYERS_COUNT])
            || rank_compare($game[PLAYERS][$pid][PREVIOUS_RANK],VICE_ASSHOLE,$game[PREVIOUS_PLAYERS_COUNT]))
            && $cards_count !== 1)
);
//President gives to asshole
return_message_on_cond(
    BAD_REQUEST_CODE,
    PRESIDENT_MUST_GIVE_TO_ASSHOLE_ERR,
    (rank_compare($game[PLAYERS][$pid][PREVIOUS_RANK],PRESIDENT,$game[PREVIOUS_PLAYERS_COUNT])
        && !rank_compare($game[PLAYERS][$to][PREVIOUS_RANK],ASSHOLE,$game[PREVIOUS_PLAYERS_COUNT])),
    $game[PLAYERS][$pid][PREVIOUS_RANK],$game[PLAYERS][$to][PREVIOUS_RANK],$pid,$to
);
//Asshole gives to president
return_message_on_cond(
    BAD_REQUEST_CODE,
    ASSHOLE_MUST_GIVE_TO_PRESIDENT_ERR,
    (rank_compare($game[PLAYERS][$pid][PREVIOUS_RANK],ASSHOLE,$game[PREVIOUS_PLAYERS_COUNT])
        && !rank_compare($game[PLAYERS][$to][PREVIOUS_RANK],PRESIDENT,$game[PREVIOUS_PLAYERS_COUNT]))
);
//Vice president gives to vice asshole
return_message_on_cond(
    BAD_REQUEST_CODE,
    VICE_PRESIDENT_MUST_GIVE_TO_VICE_ASSHOLE_ERR,
    (rank_compare($game[PLAYERS][$pid][PREVIOUS_RANK],VICE_PRESIDENT,$game[PREVIOUS_PLAYERS_COUNT])
        && !rank_compare($game[PLAYERS][$to][PREVIOUS_RANK],VICE_ASSHOLE,$game[PREVIOUS_PLAYERS_COUNT]))
);
//Vice asshole gives to vice president
return_message_on_cond(
    BAD_REQUEST_CODE,
    VICE_ASSHOLE_MUST_GIVE_TO_VICE_PRESIDENT_ERR,
    (rank_compare($game[PLAYERS][$pid][PREVIOUS_RANK],VICE_ASSHOLE,$game[PREVIOUS_PLAYERS_COUNT])
        && !rank_compare($game[PLAYERS][$to][PREVIOUS_RANK],VICE_PRESIDENT,$game[PREVIOUS_PLAYERS_COUNT]))
);

//For cards given
for($c = 0; $c < $cards_count; $c++){
    //Type is ok
    return_message_on_cond(
        BAD_REQUEST_CODE,
        UNKNOWN_TYPE_ERR,
        ($types[$c] !== DIAMONDS && $types[$c] !== SPADES && $types[$c] !== CLUBS && $types[$c] !== HEARTS),
        $types[$c]
    );
    //Number is ok
    return_message_on_cond(
        BAD_REQUEST_CODE,
        UNKNOWN_NUMBER_ERR,
        ($numbers[$c] < 0 || $numbers[$c] > 13),
        $numbers[$c]
    );

    $card = array(
        TYPE=>$types[$c],
        NUMBER=>$numbers[$c]
    );
    $index = array_search($card,$game[PLAYERS][$pid][CARDS]);
    //Player have this card
    return_message_on_cond(
        BAD_REQUEST_CODE,
        PLAYER_DONT_HAVE_THIS_CARD,
        (!$index && $index !== 0),
        $types[$c],$numbers[$c]
    );
    //Giving to the target
    array_push($game[PLAYERS][$to][CARDS],$game[PLAYERS][$pid][CARDS][$index]);
    //Removing card from the player
    unset($game[PLAYERS][$pid][CARDS][$index]);
}

//Player gives
$game[PLAYERS][$pid][GIVES] = true;

//Saving
set_game_value($gid,$game);

//Return ok
return_message_on_cond(
    OK_CODE,
    CARDS_GIVEN_OK,
    true
);
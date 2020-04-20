<?php
//Starts a game

include '../includes/includes.php';

//Required information
inspect_get(GET_GID,GET_PID);
$gid = $_GET[GET_GID];
$pid = $_GET[GET_PID];

//Testing gets
//Is leader
return_message_on_cond(
    BAD_REQUEST_CODE,
    NOT_LEADER_ERR,
    ($pid != 0),
    $pid
);
//Testing states
//Get save
$game = get_game_value($gid);
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
//Right game state
return_message_on_cond(
    BAD_REQUEST_CODE,
    WRONG_GAME_STATE_ERR,
    ($game[STATE] !== WAIT_TO_START),
    $game[STATE],WAIT_TO_START
);
//>= 2 players in the game
return_message_on_cond(
    BAD_REQUEST_CODE,
    NOT_ENOUGH_PLAYERS_ERR,
    (count($game[PLAYERS]) < 2),
    count($game[PLAYERS]),2
);

//Resetting
$game[REVOLUTION] = false;
$game[AMOUNT] = 0;
$game[GAME_COUNT] += 1;
$game[SEQUENCE] = 0;
$game[TURN] = 0;
$game[PREVIOUS_CARDS_PUT] = array();
$game[STACK] = $all_cards; //All cards in stack
$players_count = count($game[PLAYERS]);
for($p = 0; $p < $players_count; $p++){
    $game[PLAYERS][$p][CARDS] = array();
    $game[PLAYERS][$p][RANK] = 0;
    $game[PLAYERS][$p][GIVES] = false;
    $game[PLAYERS][$p][FOLD] = false;
}

//Shuffle
for($p = array_rand($game[PLAYERS]); count($game[STACK]) > 0; $p++){
    if($p >= $players_count)
        $p = 0;

    $card = array_rand($game[STACK]);
    array_push($game[PLAYERS][$p][CARDS],$game[STACK][$card]);
    unset($game[STACK][$card]);
}

//If first game
if($game[GAME_COUNT] == 1){
    $heart_queen = array(
        TYPE=>HEARTS,
        NUMBER=>12
    );
    for($p = 0; $p < $players_count; $p++){
        $index = array_search($heart_queen,$game[PLAYERS][$p][CARDS]);
        //The player who had hearts queen begins
        if($index){
            $game[TURN] = $p;
            break;
        }
    }
}else{
    //The asshole begins
    for($p = 0; $p < $players_count; $p++){
        if(rank_compare($game[PLAYERS][$p][PREVIOUS_RANK],ASSHOLE,$game[PREVIOUS_PLAYERS_COUNT])){
            $game[TURN] = $p;
            break;
        }
    }
}

//If first game: play directly, else: exchange state
$game[STATE] = $game[GAME_COUNT] == 1 ? PLAY : EXCHANGE;
set_game_value($gid,$game);

//Return ok
return_message_on_cond(
    OK_CODE,
    GAME_STARTED_OK,
    true
);

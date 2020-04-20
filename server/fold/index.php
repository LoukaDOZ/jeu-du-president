<?php
//Makes a player fold

include '../includes/includes.php';

//Required information
inspect_get(GET_GID,GET_PID);
$gid = $_GET[GET_GID];
$pid = $_GET[GET_PID];

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
//Player already finished
return_message_on_cond(
    BAD_REQUEST_CODE,
    PLAYER_ALREADY_FINISHED_ERR,
    ($game[PLAYERS][$pid][RANK] > 0)
);
//Right game state
return_message_on_cond(
    BAD_REQUEST_CODE,
    WRONG_GAME_STATE_ERR,
    ($game[STATE] !== PLAY),
    $game[STATE],PLAY
);
//Player turn
return_message_on_cond(
    BAD_REQUEST_CODE,
    NOT_PLAYER_TURN_ERR,
    ($game[TURN] != $pid)
);

//Making player fold
$game[PLAYERS][$pid][FOLD] = true;

//Checking players who don't finish an don't fold
$next = false;
foreach ($game[PLAYERS] as $player){
    if(!$player[FOLD] && !$player[RANK] > 0)
        $next = true;
}

//If a player don't fold
if($next)
    $game[TURN] = next_turn($game[TURN],$game[PLAYERS]);
else{   //If all players who don't finish folded
    end_round($game);

    //If no one put a card
    if($game[LAST_PUT] < 0){
        $pid_int = intval($pid) + 1;
        //Make a complete loop
        $game[TURN] = $pid_int >= count($game[PLAYERS]) ? 0 : $pid_int;
    }else{
        //Turn to the last player who puts but if he finished, next turn
        $game[TURN] = ($game[PLAYERS][$game[LAST_PUT]][RANK] > 0) ? next_turn($game[TURN],$game[PLAYERS]) : $game[LAST_PUT];
    }
}

//Resetting sequence
$game[SEQUENCE] = 0;
//Saving
set_game_value($gid,$game);

//Return ok
return_message_on_cond(
    OK_CODE,
    PLAYER_FOLDED_OK,
    true
);
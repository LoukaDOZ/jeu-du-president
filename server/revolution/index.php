<?php
//Makes revolution

include '../includes/includes.php';

//Required information
inspect_get(GET_GID,GET_PID,GET_DO);
$gid = $_GET[GET_GID];
$pid = $_GET[GET_PID];
$do = $_GET[GET_DO];
$do_true = "true";

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
    ($game[STATE] !== PLAY),
    $game[STATE],PLAY
);
//Is player turn
return_message_on_cond(
    BAD_REQUEST_CODE,
    NOT_PLAYER_TURN_ERR,
    ($game[TURN] != $pid)
);
//Player can do revolution
return_message_on_cond(
    BAD_REQUEST_CODE,
    CANT_DO_REVOLUTION_ERR,
    (!$game[PLAYERS][$pid][CAN_REVOLUTION])
);

//If player want to do revolution
if($do == $do_true)
    $game[REVOLUTION] = !$game[REVOLUTION];

$game[PLAYERS][$pid][CAN_REVOLUTION] = false;
$last_card = end($game[STACK]);
$game[SEQUENCE] = 0;
//If revolution and last card number is 3
if($game[REVOLUTION] && $last_card[NUMBER] == 3){
    //Keep hand
    $game[TURN] = intval($pid);
    //End round
    end_round($game);
}else{
    //Skip next player ({card} or nothing but all 4 cards already put)
    $game[TURN] = next_turn($game[TURN],$game[PLAYERS]);
    $game[TURN] = next_turn($game[TURN],$game[PLAYERS]);
}

//Saving
set_game_value($gid,$game);

//If player wanted revolution
if($do === $do_true){
    //Revolution done
    return_message_on_cond(
        OK_CODE,
        REVOLUTION_DONE_OK,
        true
    );
}else{
    //Revolution not done
    return_message_on_cond(
        OK_CODE,
        REVOLUTION_NOT_DONE_OK,
        true
    );
}

<?php
//Makes a player pass his turn

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
//Player finished
return_message_on_cond(
    BAD_REQUEST_CODE,
    PLAYER_ALREADY_FINISHED_ERR,
    ($game[PLAYERS][$pid][RANK] > 0),
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

//Next turn
$game[TURN] = next_turn($game[TURN],$game[PLAYERS]);
//Ending sequence
$game[SEQUENCE] = 0;
//Saving
set_game_value($gid,$game);

//Return ok
return_message_on_cond(
    OK_CODE,
    PLAYER_PASSED_OK,
    true
);
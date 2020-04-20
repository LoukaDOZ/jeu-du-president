<?php
//To pass to the next game state

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
    ($game[STATE] !== FINISHED && $game[STATE] !== EXCHANGE),
    $game[STATE],WAIT_TO_START
);

//If is game finished -> back to wait to start
if($game[STATE] === FINISHED){
    //Changing "previous" states
    $players_count = count($game[PLAYERS]);
    $game[PREVIOUS_PLAYERS_COUNT] = $players_count;
    for($p = 0; $p < $players_count; $p++){
        $game[PLAYERS][$p][PREVIOUS_RANK] = $game[PLAYERS][$p][RANK];
    }

    //Changing game state
    $game[STATE] = WAIT_TO_START;
    //Saving
    set_game_value($gid,$game);

    //Return ok
    return_message_on_cond(
        OK_CODE,
        GAME_FINISHED_OK,
        true
    );
}else{  //If is exchange -> start playing
    //Changing game state
    $game[STATE] = PLAY;
    //Saving
    set_game_value($gid,$game);

    //Return ok
    return_message_on_cond(
        OK_CODE,
        EXCHANGE_FINISHED_OK,
        true
    );
}

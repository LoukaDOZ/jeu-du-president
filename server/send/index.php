<?php
//Send message in the chat

include '../includes/includes.php';

//Required information
inspect_get(GET_GID,GET_PID,GET_SEND);
$gid = $_GET[GET_GID];
$pid = $_GET[GET_PID];
$send = $_GET[GET_SEND];

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

$message = array(
    "pid"=>intval($pid),
    "message"=>utf8_encode($send)
);
array_push($game[CHAT],$message);

//Saving
set_game_value($gid,$game);

//Return ok
return_message_on_cond(
    OK_CODE,
    MESSAGE_SENT_OK,
    true
);
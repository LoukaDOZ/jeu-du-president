<?php
//Get information of a game

include '../includes/includes.php';

//Required information
inspect_get(GET_GID);
$gid = $_GET[GET_GID];

//Testing states
//Get save
$game = get_game_value($gid);
//Game exists
return_message_on_cond(
    BAD_REQUEST_CODE,
    GAME_DOESNT_EXIST_ERR,
    ($game === null)
);

//Returning game to json
echo json_encode($game,JSON_FORCE_OBJECT);

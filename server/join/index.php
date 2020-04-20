<?php
//Makes a player join a game

include '../includes/includes.php';

//Required information
inspect_get(GET_GID,GET_NAME);
$gid = $_GET[GET_GID];
$player_name = $_GET[GET_NAME];

//Testing states
//Get save
$game = get_game_value($gid);
$count = count($game[PLAYERS]);
//Game exists
return_message_on_cond(
    BAD_REQUEST_CODE,
    GAME_DOESNT_EXIST_ERR,
    ($game === null)
);
//Right game state
return_message_on_cond(
    BAD_REQUEST_CODE,
    WRONG_GAME_STATE_ERR,
    ($game[STATE] !== WAIT_TO_START),
    $game[STATE]
);
//Is game full
return_message_on_cond(
    BAD_REQUEST_CODE,
    GAME_IS_FULL_ERR,
    ($count >= 8),
    $count
);

//Adding new player
$player = new_player($count,$player_name);
array_push($game[PLAYERS],$player);
//Saving
set_game_value($gid,$game);

//Returning json of player's information
echo json_encode($player,JSON_FORCE_OBJECT);

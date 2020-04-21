<?php
//Creates new game

include '../includes/includes.php';

//Required information
inspect_get(GET_NAME);
$player_name = $_GET[GET_NAME];

//Getting unique random key
$key = random_key();
while(test_exist_key($key)){
    $key = random_key();
}

//New game
$base_content = new_game($key);
array_push($base_content[PLAYERS],new_player(0,utf8_encode($player_name)));

$base_content = json_encode($base_content,JSON_FORCE_OBJECT);
//Saving new game
save_new_game($key,$base_content);

//Returning json of game information
echo $base_content;

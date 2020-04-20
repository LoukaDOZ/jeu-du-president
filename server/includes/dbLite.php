<?php
//Database communications

function open(){
    //Connect
    $link = new PDO("sqlite:../db/cards.db");
    //Is connected
    return_message_on_cond(
        INTERNAL_SERVER_ERROR_CODE,
        DB_OPEN_ERR,
        (!$link)
    );
    //Returning link
    return $link;
}

//Get game information
function get_game_value($key){
    //Connect
    $link = open();

    //Selected
    $result = $link->query("SELECT value FROM tdc WHERE id='".$key."'");
    //Is ok
    return_message_on_cond(
        INTERNAL_SERVER_ERROR_CODE,
        DB_GET_GAME_ERR,
        (!$result)
    );

    //id unique so always 1 result max
    foreach ($result as $row){
        //Return information
        return json_decode($row['value'], true);
    }

    return null;
}

//Set game information
function set_game_value($key,$array){
    //Connect
    $link = open();

    //Update
    $result = $link->query("UPDATE tdc SET value='".json_encode($array,JSON_FORCE_OBJECT)."' WHERE id='".$key."'");
    //Is ok
    return_message_on_cond(
        INTERNAL_SERVER_ERROR_CODE,
        DB_SET_GAME_ERR,
        (!$result)
    );
}

//Saves the new game
function save_new_game($id,$json){
    //Connect
    $link = open();

    //Insert
    $result = $link->query("INSERT INTO tdc (`id`, `value`) VALUES ('".$id."','".$json."');");
    //Is ok
    return_message_on_cond(
        INTERNAL_SERVER_ERROR_CODE,
        DB_CREATE_GAME_ERR,
        (!$result)
    );
}

//Test if a game key already exists
function test_exist_key($key){
    //Connect
    $link = open();

    //Get a game with same key
    $result = $link->query("SELECT value FROM tdc WHERE id='".$key."'");

    //If there is a game with the same key
    foreach ($result as $row){
        return true;
    }

    //Else, no game with same key
    return false;
}

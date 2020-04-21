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
    $request = $link->prepare("SELECT value FROM tdc WHERE id=?");
    $result = $request->execute(array($key));
    //Is ok
    return_message_on_cond(
        INTERNAL_SERVER_ERROR_CODE,
        DB_GET_GAME_ERR,
        (!$result)
    );

    //id unique so always 1 result max
    foreach ($request->fetchAll() as $row){
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
    $request = $link->prepare("UPDATE tdc SET value=? WHERE id=?");
    $result = $request->execute(array(json_encode($array,JSON_FORCE_OBJECT),$key));
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
    $request = $link->prepare("INSERT INTO tdc (`id`, `value`) VALUES (?,?)");
    $result = $request->execute(array($id,$json));
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
    $request = $link->prepare("SELECT value FROM tdc WHERE id=?");
    $request->execute(array($key));

    //If there is a game with the same key
    foreach ($request->fetchAll() as $row){
        return true;
    }

    //Else, no game with same key
    return false;
}

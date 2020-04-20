<?php
//Database communications

function open(){
    //Connect
    $link = mysqli_connect("localhost","root","","cards");
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
    $result = mysqli_query($link,"SELECT value FROM tdc WHERE id='".$key."'");
    //Is ok
    return_message_on_cond(
        INTERNAL_SERVER_ERROR_CODE,
        DB_GET_GAME_ERR,
        (!$result || mysqli_num_rows($result) <= 0)
    );
    
    //Closing
    mysqli_close($link);

    //id unique so always 1 result
    while ($row = mysqli_fetch_assoc($result)) {
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
    $result = mysqli_query($link,"UPDATE tdc SET value='".json_encode($array,JSON_FORCE_OBJECT)."' WHERE id='".$key."'");
    //Is ok
    return_message_on_cond(
        INTERNAL_SERVER_ERROR_CODE,
        DB_SET_GAME_ERR,
        (!$result)
    );
    
    //Closing
    mysqli_close($link);
}

//Saves the new game
function save_new_game($id,$json){
    //Connect
    $link = open();

    //Insert
    $result = mysqli_query($link,"INSERT INTO tdc (`id`, `value`) VALUES ('".$id."','".$json."');");
    //Is ok
    return_message_on_cond(
        INTERNAL_SERVER_ERROR_CODE,
        DB_CREATE_GAME_ERR,
        (!$result)
    );
    
    //Closing
    mysqli_close($link);
}

//Test if a game key already exists
function test_exist_key($key){
    //Connect
    $link = open();

    //Get a game with same key
    $result = mysqli_query($link,"SELECT value FROM tdc WHERE id='".$key."'");
    
    //Closing
    mysqli_close($link);

    //If there is a game with the same key
    while ($row = mysqli_fetch_assoc($result)){
        return true;
    }

    //Else, no game with same key
    return false;
}
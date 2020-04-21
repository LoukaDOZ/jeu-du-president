<?php
//Database communications

function open(){
    //Connect
    $link = mysqli_connect("localhost","login","password","cards");
    //Is connected
    return_message_on_cond(
        INTERNAL_SERVER_ERROR_CODE,
        DB_OPEN_ERR,
        ((mysqli_errno($link)))
    );
    //Returning link
    return $link;
}

//Get game information
function get_game_value($key){
    //Connect
    $link = open();

    //Selected
    $request = mysqli_prepare($link,"SELECT value FROM tdc WHERE id=?");
    mysqli_stmt_bind_param($request,"s",$key);
    mysqli_stmt_execute($request);
    $result = mysqli_stmt_get_result($request);
    
    //Closing
    mysqli_stmt_close($request);
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
    $request = mysqli_prepare($link,"UPDATE tdc SET value=? WHERE id=?");
    mysqli_stmt_bind_param($request,"ss",json_encode($array,JSON_FORCE_OBJECT),$key);
    mysqli_stmt_execute($request);
    $result = mysqli_stmt_get_result($request);
    //Is ok
    return_message_on_cond(
        INTERNAL_SERVER_ERROR_CODE,
        DB_SET_GAME_ERR,
        ((mysqli_errno($link)))
    );
    
    //Closing
    mysqli_stmt_close($request);
    mysqli_close($link);
}

//Saves the new game
function save_new_game($id,$json){
    //Connect
    $link = open();

    //Insert
    $request = mysqli_prepare($link,"INSERT INTO tdc (`id`, `value`) VALUES (?,?);");
    mysqli_stmt_bind_param($request,"ss",$id,$json);
    mysqli_stmt_execute($request);
    $result = mysqli_stmt_get_result($request);
    //Is ok
    return_message_on_cond(
        INTERNAL_SERVER_ERROR_CODE,
        DB_CREATE_GAME_ERR,
        (mysqli_errno($link))
    );
    
    //Closing
    mysqli_stmt_close($request);
    mysqli_close($link);
}

//Test if a game key already exists
function test_exist_key($key){
    //Connect
    $link = open();

    //Get a game with same key
    $request = mysqli_prepare($link,"SELECT value FROM tdc WHERE id=?");
    mysqli_stmt_bind_param($request,"s",$key);
    mysqli_stmt_execute($request);
    $result = mysqli_stmt_get_result($request);
    
    //Closing
    mysqli_stmt_close($request);
    mysqli_close($link);

    //If there is a game with the same key
    while ($row = mysqli_fetch_assoc($result)){
        return true;
    }

    //Else, no game with same key
    return false;
}

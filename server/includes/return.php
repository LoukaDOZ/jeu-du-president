<?php
//Return functions

//Return a json message if the condition is true
function return_message_on_cond($http_code, $code, $cond){
    if ($cond){
        $message = array(
            CODE => $code,
            ARGS => array()
        );
        for ($count = 3; $count < func_num_args(); $count++)
            array_push($message[ARGS], func_get_arg($count));

        header("HTTP/1.1 ".$http_code);
        echo json_encode($message);
        exit;
    }
}

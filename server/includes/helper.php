<?php
//Helper functions

//Check indexes in $_GET
function inspect_get(){
    //For all indexes
    foreach (func_get_args() as $index){
        //Index exists
        return_message_on_cond(
            INTERNAL_SERVER_ERROR_CODE,
            MISSING_ERR,
            (!isset($_GET[$index])),
            $index
        );
        //Index is not null
        return_message_on_cond(
            BAD_REQUEST_CODE,
            UNDEFINED_ERR,
            ($_GET[$index] === ''),
            $index
        );
    }
}

//Get random key
function random_key(){
    $string = '';
    $length = 10;
    $chars = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';

    for($i = 0; $i < $length; $i++){
        $string .= $chars[rand(0, strlen($chars)-1)];
    }

    return $string;
}

//Pass to the next turn
function next_turn($turn,array $players){
    do{
        $turn++;
        if($turn >= count($players))
            $turn = 0;
    }while($players[$turn][FOLD] || $players[$turn][RANK] > 0);
    //While the player folded or finished, pass to the next player

    return $turn;
}

//Compare to cards
function compare_cards($compare,$to,$revolution){
    //If same
    if($compare === $to) return 0;

    //If revolution
    if($revolution){
        switch ($compare){
            case 3:
                return 1;
            case 2:
                return -1;
            case 1:
                return $to === 2 ? 1 : -1;
            default:
                if($to == 3) return -1;
                if($to === 1 || $to === 2) return 1;
                else return $compare < $to ? 1 : -1;
        }
    }else{
        switch ($compare){
            case 2:
                return 1;
            case 1:
                return $to === 2 ? -1 : 1;
            default:
                if($to === 1 || $to === 2) return -1;
                else return $compare > $to ? 1 : -1;
        }
    }
}

//Get a new rank
function get_rank(array $players,$start_top){
    $ranks = array();
    $players_count = count($players);
    //Getting already given ranks
    for($p = 0; $p < $players_count; $p++){
        if($players[$p][RANK] > 0)
            array_push($ranks,$players[$p][RANK]);
    }

    $res = 0;
    //If start from first place
    if($start_top){
        //Start rank 1 and increase until a rank is not given
        for($i = 1; $i < $players_count + 1; $i++){
            $s = array_search($i,$ranks);
            if(!$s && $s !== 0) {
                $res = $i;
                break;
            }
        }
    }else{
        //Start last rank and decrease until a rank is not given
        for($i = $players_count; $i > 0; $i--){
            $s = array_search($i,$ranks);
            if(!$s && $s !== 0) {
                $res = $i;
                break;
            }
        }
    }

    return $res;
}

//Compare rank with rank name
function rank_compare($rank,$compare,$players_count){
    switch ($compare){
        case PRESIDENT:
            return ($rank == 1);
        case VICE_PRESIDENT:
            return ($rank == 2 && $players_count > 3);
        case NEUTRAL:
            $non_neutral = $players_count > 3 ? 2 : 1;
            return (($rank > $non_neutral && $rank <= ($players_count - $non_neutral)) || $rank == 0);
        case VICE_ASSHOLE:
            return ($rank == ($players_count - 1) && $players_count > 3);
        case ASSHOLE:
            return ($rank === $players_count);
        default:
            return false;
    }
}

//End round by resetting round information
function end_round(array &$game){
    $game[SEQUENCE] = 0;
    $game[REVOLUTION] = false;

    $stack_count = count($game[STACK]);
    $game[PREVIOUS_CARDS_PUT] = array();
    for($i = ($stack_count - $game[AMOUNT]); $i < $stack_count; $i++){
        array_push($game[PREVIOUS_CARDS_PUT],$game[STACK][$i]);
    }

    $game[STACK] = array();
    $game[AMOUNT] = 0;
    for($p = 0; $p < count($game[PLAYERS]); $p++){
        $game[PLAYERS][$p][CAN_REVOLUTION] = false;
        $game[PLAYERS][$p][FOLD] = false;
    }
}
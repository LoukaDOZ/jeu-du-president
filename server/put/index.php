<?php
//To put cards in the stack

include '../includes/includes.php';

//Required information
inspect_get(GET_GID,GET_PID,GET_TYPES,GET_NUMBER);
$gid = $_GET[GET_GID];
$pid = $_GET[GET_PID];
$types = explode(MULTIPLE_VALUES_DELIMITER,$_GET[GET_TYPES]);
$number = intval($_GET[GET_NUMBER]);
$cards_count = count($types);

//Testing gets
//Between 1 and 4 cards
return_message_on_cond(
    BAD_REQUEST_CODE,
    NOT_RIGHT_AMOUNT_OF_CARDS_ERR,
    ($cards_count <= 0 || $cards_count > 4),
    $cards_count,1,4
);

//Testing states
//Get save
$game = get_game_value($gid);
$last = end($game[STACK]);
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
//Player finished
return_message_on_cond(
    BAD_REQUEST_CODE,
    PLAYER_ALREADY_FINISHED_ERR,
    ($game[PLAYERS][$pid][RANK] > 0)
);
//Right game state
return_message_on_cond(
    BAD_REQUEST_CODE,
    WRONG_GAME_STATE_ERR,
    ($game[STATE] !== PLAY),
    $game[STATE],PLAY
);
//Player have enough cards
return_message_on_cond(
    BAD_REQUEST_CODE,
    DONT_HAVE_THIS_AMOUNT_OF_CARDS_ERR,
    (count($game[PLAYERS][$pid][CARDS]) < $cards_count),
    count($game[PLAYERS][$pid][CARDS]),$cards_count
);
//Put the right amount of cards
return_message_on_cond(
    BAD_REQUEST_CODE,
    NOT_RIGHT_AMOUNT_OF_CARDS_ERR,
    ($cards_count != $game[AMOUNT] && $game[AMOUNT] != 0),
    $game[AMOUNT],$cards_count
);
//Is player turn
return_message_on_cond(
    BAD_REQUEST_CODE,
    NOT_PLAYER_TURN_ERR,
    ($game[TURN] != $pid)
);
//Player already folded
return_message_on_cond(
    BAD_REQUEST_CODE,
    PLAYER_ALREADY_FOLDED_ERR,
    ($game[PLAYERS][$pid][FOLD])
);
//Put the same or equal card number as the last card
return_message_on_cond(
    BAD_REQUEST_CODE,
    LAST_NUMBER_NOT_FOLLOWED_ERR,
    (compare_cards($number,$last[NUMBER],$game[REVOLUTION]) < 0),
    ($game[REVOLUTION] ? 'inferior' : 'superior'),$last[NUMBER],$number
);
//Put the same card number as sequence
return_message_on_cond(
    BAD_REQUEST_CODE,
    SEQUENCE_NOT_FOLLOWED_ERR,
    ($game[SEQUENCE] > 0 && $number !== $game[SEQUENCE]),
    $game[SEQUENCE],$number
);
//Unknown number
return_message_on_cond(
    BAD_REQUEST_CODE,
    UNKNOWN_NUMBER_ERR,
    ($number <= 0 || $number > 13),
    $number
);

$stack_count = count($game[STACK]);
$stack_index = ($stack_count - (4 - $cards_count));
$can_revolution = true;
//If 4 cards at least
if($stack_index >= 0) {
    for (; $stack_index < $stack_count; $stack_index++) {
        //If previous cards don't have the same number as the put cards
        if ($game[STACK][$stack_index][NUMBER] != $number)
            $can_revolution = false;
    }
}else   //Else, can't do revolution
    $can_revolution = false;

//For each put cards
foreach($types as $type){
    //Unknown type
    return_message_on_cond(
        BAD_REQUEST_CODE,
        UNKNOWN_TYPE_ERR,
        ($type !== DIAMONDS && $type !== SPADES && $type !== CLUBS && $type !== HEARTS),
        $type
    );

    $card = array(
        TYPE=>$type,
        NUMBER=>$number
    );
    $index = array_search($card,$game[PLAYERS][$pid][CARDS]);
    //If player have this card
    return_message_on_cond(
        BAD_REQUEST_CODE,
        PLAYER_DONT_HAVE_THIS_CARD,
        (!$index && $index !== 0),
        $type,$number
    );
    //Putting card in stack
    array_push($game[STACK],$game[PLAYERS][$pid][CARDS][$index]);
    //Removing card from player cards
    unset($game[PLAYERS][$pid][CARDS][$index]);
}

$stack_count = count($game[STACK]);
$new_seq = true;
//If no sequence and amount of cards to put < 3
if($game[SEQUENCE] === 0 && $game[AMOUNT] < 3){
    $seq_index = $game[AMOUNT] === 1 ? 2 : 4;
    $c = $stack_count - $seq_index;

    //If enough cards in stack
    if($c >= 0) {
        for (; $c < $stack_count; $c++) {
            //If last 2 or 4 cards in stack haven't the same number
            if ($game[STACK][$c][NUMBER] !== $number)
                $new_seq = false;
        }
    }else
        $new_seq = false;

    //New sequence
    if($new_seq)
        $game[SEQUENCE] = $number;
}

//If is new round
if($game[AMOUNT] == 0){
    //Setting new round amount
    $game[AMOUNT] = $cards_count;
}

$finished = (count($game[PLAYERS][$pid][CARDS]) === 0) ? true : false;
$keep_hand = false;
$stop_card = $game[REVOLUTION] ? 3 : 2;
//If put a stopping card (2 or (3 in revolution))
if(compare_cards($number,$stop_card,$game[REVOLUTION]) === 0){
    //Keep hand if don't have finished
    $keep_hand = !$finished;
    //Resetting round
    end_round($game);
}

//If finished
$last_unfinished = -1;
if($finished){
    //Setting player rank
    $res = get_rank($game[PLAYERS],!(compare_cards($number,$stop_card,$game[REVOLUTION]) === 0));
    $game[PLAYERS][$pid][RANK] = $res;
    $game[PLAYERS][$pid][SCORE] += (count($game[PLAYERS]) - $res) * 10;

    $all_fold = true;
    foreach ($game[PLAYERS] as $player){
        //If a player hasn't finished : $player[PID], if >= 2 players haven't finished : -2
        if($player[RANK] == 0)
            $last_unfinished = $last_unfinished === -1 ? $player[PID] : -2;

        if(!$player[FOLD] && $player[RANK] == 0)
            $all_fold = false;
    }

    //If only one player left
    if($last_unfinished >= 0){
        //Give it a rank
        $rank = get_rank($game[PLAYERS],!($number === $stop_card));
        $game[PLAYERS][$last_unfinished][RANK] = $rank;
        $game[PLAYERS][$last_unfinished][SCORE] += (count($game[PLAYERS]) - $rank) * 10;
        //Game finished
        $game[STATE] = FINISHED;
    }else if($all_fold) //If everyone folded
        //End round
        end_round($game);
}

//If can do revolution
if($can_revolution) {
    $game[PLAYERS][$pid][CAN_REVOLUTION] = true;
    //Keep hand
    $game[TURN] = intval($pid);
} else if($keep_hand)
    //Keep hand
    $game[TURN] = intval($pid);
else if($last_unfinished < 0)
    //Next turn if, at least, one player left
    $game[TURN] = next_turn($game[TURN], $game[PLAYERS]);

//Setting last player who put
$game[LAST_PUT] = intval($pid);
//Saving
set_game_value($gid,$game);

//Return ok
return_message_on_cond(
    OK_CODE,
    PLAYER_PUT_OK,
    true
);


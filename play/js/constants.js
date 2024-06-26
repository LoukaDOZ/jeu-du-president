//Constants

//Base URL
const BASE_URL = "http://localhost:8082/";

//Arrays indexes
//Game model
const GID = "gid";
const GAME_COUNT = "game count";
const TURN = "turn";
const SEQUENCE = "sequence";
const LAST_PUT = "last put";
const STATE = "state";
const PREVIOUS_PLAYERS_COUNT = "previous players count";
const REVOLUTION = "revolution";
const AMOUNT = "amount";
const STACK = "stack";
const PREVIOUS_CARDS_PUT = "previous cards put";
const PLAYERS = "players";
const CHAT = "chat";

//Game state
const WAIT_TO_START = "wait to start";
const EXCHANGE = "exchange";
const PLAY = "play";
const FINISHED = "finished";

//Player model
const PID = "pid";
const NAME = "name";
const SCORE = "score";
const RANK = "rank";
const PREVIOUS_RANK = "previous rank";
const GIVES = "gives";
const FOLD = "fold";
const CAN_REVOLUTION = "revolution";
const CARDS = "cards";

//Card model
const TYPE = "type";
const NUMBER = "number";
const DIAMONDS = "diamonds";
const CLUBS = "clubs";
const HEARTS = "hearts";
const SPADES = "spades";

//Ranks
const PRESIDENT = "president";
const VICE_PRESIDENT = "vice president";
const NEUTRAL = "neutral";
const VICE_ASSHOLE = "vice asshole";
const ASSHOLE = "asshole";

//Get indexes
const MULTIPLE_VALUES_DELIMITER = " ";
const GET_GID = "gid";
const GET_PID = "pid";
const GET_TO = "to";
const GET_TYPES = "types";
const GET_NUMBERS = "numbers";
const GET_NUMBER = "number";
const GET_NAME = "name";
const GET_DO = "do";
const GET_SEND = "send";

//Return messages
const CODE = "code";
const ARGS = "args";
//HTTP codes
const OK_CODE = "200 OK";
const BAD_REQUEST_CODE = "400 Bad Request";
const INTERNAL_SERVER_ERROR_CODE = "500 Internal Server Error";
//Codes
//Error
const DB_OPEN_ERR = "DB_OPEN_ERR";
const DB_GET_GAME_ERR = "DB_GET_GAME_ERR";
const DB_SET_GAME_ERR = "DB_SET_GAME_ERR";
const DB_CREATE_GAME_ERR = "DB_CREATE_GAME_ERR";
const MISSING_ERR = "MISSING_ERR";
const UNDEFINED_ERR = "UNDEFINED_ERR";
const GAME_DOESNT_EXIST_ERR = "GAME_DOESNT_EXIST_ERR";
const NOT_LEADER_ERR = "NOT_LEADER_ERR";
const NOT_BELONG_TO_THIS_GAME_ERR = "NOT_BELONG_TO_THIS_GAME_ERR";
const WRONG_GAME_STATE_ERR = "WRONG_GAME_STATE_ERR";
const PLAYER_ALREADY_FINISHED_ERR = "PLAYER_ALREADY_FINISHED_ERR";
const NOT_PLAYER_TURN_ERR = "NOT_PLAYER_TURN_ERR";
const NUMBERS_AND_TYPES_NOT_SAME_AMOUNT_ERR = "NUMBERS_AND_TYPES_NOT_SAME_AMOUNT_ERR";
const NOT_RIGHT_AMOUNT_OF_CARDS_ERR = "NOT_RIGHT_AMOUNT_OF_CARDS_ERR";
const NO_EXCHANGE_AT_THIS_GAME_COUNT_ERR = "NO_EXCHANGE_AT_THIS_GAME_COUNT_ERR";
const DONT_HAVE_THIS_AMOUNT_OF_CARDS_ERR = "DONT_HAVE_THIS_AMOUNT_OF_CARDS_ERR";
const NEUTRAL_DONT_GIVE_CARD_ERR = "NEUTRAL_DONT_GIVE_CARD_ERR";
const PLAYER_ALREADY_GIVEN_ERR = "PLAYER_ALREADY_GIVEN_ERR";
const MUST_GIVE_2_CARDS_ERR = "MUST_GIVE_2_CARDS_ERR";
const MUST_GIVE_1_CARD_ERR = "MUST_GIVE_1_CARD_ERR";
const PRESIDENT_MUST_GIVE_TO_ASSHOLE_ERR = "PRESIDENT_MUST_GIVE_TO_ASSHOLE_ERR";
const VICE_PRESIDENT_MUST_GIVE_TO_VICE_ASSHOLE_ERR = "VICE_PRESIDENT_MUST_GIVE_TO_VICE_ASSHOLE_ERR";
const ASSHOLE_MUST_GIVE_TO_PRESIDENT_ERR = "ASSHOLE_MUST_GIVE_TO_PRESIDENT_ERR";
const VICE_ASSHOLE_MUST_GIVE_TO_VICE_PRESIDENT_ERR = "VICE_ASSHOLE_MUST_GIVE_TO_VICE_PRESIDENT_ERR";
const UNKNOWN_TYPE_ERR = "UNKNOWN_TYPE_ERR";
const UNKNOWN_NUMBER_ERR = "UNKNOWN_NUMBER_ERR";
const PLAYER_DONT_HAVE_THIS_CARD = "PLAYER_DONT_HAVE_THIS_CARD";
const GAME_IS_FULL_ERR = "GAME_IS_FULL_ERR";
const NOT_ENOUGH_PLAYERS_ERR = "NOT_ENOUGH_PLAYERS_ERR";
const PLAYER_ALREADY_FOLDED_ERR = "PLAYER_ALREADY_FOLDED_ERR";
const LAST_NUMBER_NOT_FOLLOWED_ERR = "LAST_NUMBER_NOT_FOLLOWED_ERR";
const SEQUENCE_NOT_FOLLOWED_ERR = "SEQUENCE_NOT_FOLLOWED_ERR";
const CANT_DO_REVOLUTION_ERR = "CANT_DO_REVOLUTION_ERR";
const NETWORK_ERR = "NETWORK_ERR";
//OK
const GAME_FINISHED_OK = "GAME_FINISHED_OK";
const EXCHANGE_FINISHED_OK = "EXCHANGE_FINISHED_OK";
const PLAYER_FOLDED_OK = "PLAYER_FOLDED_OK";
const CARDS_GIVEN_OK = "CARDS_GIVEN_OK";
const PLAYER_PASSED_OK = "PLAYER_PASSED_OK";
const PLAYER_PUT_OK = "PLAYER_PUT_OK";
const REVOLUTION_DONE_OK = "REVOLUTION_DONE_OK";
const REVOLUTION_NOT_DONE_OK = "REVOLUTION_NOT_DONE_OK";
const GAME_STARTED_OK = "GAME_STARTED_OK";
const MESSAGE_SENT_OK = "MESSAGE_SENT_OK";

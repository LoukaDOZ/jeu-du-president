const showError = function(message){
    document.querySelector('#toastBody').innerText = message;
    $('#errors').toast('show');
};

const showRules = function(){
    $('#rules').modal('toggle');
};

const isInputOk = function (name) {
    return !name.match(new RegExp("[&%'\"/]"));
};

const decodeError = function (error) {
    let err = "";
    let args = [];

    switch (error.code) {
        case DB_OPEN_ERR:
        case DB_GET_GAME_ERR:
        case DB_SET_GAME_ERR:
        case DB_CREATE_GAME_ERR:
            showError("Une erreur du serveur est survenue : impossible d'effectuer une action sur la partie : " + error.code);
            break;
        case MISSING_ERR:
        case UNDEFINED_ERR:
            err = "Une erreur de requête vers au serveur est survenue : requête incomplete : " + error.code;
            args = Object.values(error.args);
            err += args.length > 0 ? " :" : "";
            for(arg of args){
                err += " " + arg;
            }
            showError(err);
            break;
        case GAME_IS_FULL_ERR:
            showError("Impossible de rejoindre : trop de joueurs");
            break;
        case GAME_DOESNT_EXIST_ERR:
            showError("Cette partie n'existe pas : vérifiez la clé");
            break;
        case WRONG_GAME_STATE_ERR:
        case NOT_LEADER_ERR:
        case NOT_BELONG_TO_THIS_GAME_ERR:
        case PLAYER_ALREADY_FINISHED_ERR:
        case NOT_PLAYER_TURN_ERR:
        case NUMBERS_AND_TYPES_NOT_SAME_AMOUNT_ERR:
        case NOT_RIGHT_AMOUNT_OF_CARDS_ERR:
        case NO_EXCHANGE_AT_THIS_GAME_COUNT_ERR:
        case DONT_HAVE_THIS_AMOUNT_OF_CARDS_ERR:
        case NEUTRAL_DONT_GIVE_CARD_ERR:
        case PLAYER_ALREADY_GIVEN_ERR:
        case MUST_GIVE_2_CARDS_ERR:
        case MUST_GIVE_1_CARD_ERR:
        case PRESIDENT_MUST_GIVE_TO_ASSHOLE_ERR:
        case VICE_PRESIDENT_MUST_GIVE_TO_VICE_ASSHOLE_ERR:
        case ASSHOLE_MUST_GIVE_TO_PRESIDENT_ERR:
        case VICE_ASSHOLE_MUST_GIVE_TO_VICE_PRESIDENT_ERR:
        case UNKNOWN_TYPE_ERR:
        case UNKNOWN_NUMBER_ERR:
        case PLAYER_DONT_HAVE_THIS_CARD:
        case NOT_ENOUGH_PLAYERS_ERR:
        case PLAYER_ALREADY_FOLDED_ERR:
        case LAST_NUMBER_NOT_FOLLOWED_ERR:
        case SEQUENCE_NOT_FOLLOWED_ERR:
        case CANT_DO_REVOLUTION_ERR:
            err = "Une erreur de requête vers au serveur est survenue : état incorrect : " + error.code;
            args = Object.values(error.args);
            err += args.length > 0 ? " :" : "";
            for(arg of args){
                err += " " + arg;
            }
            showError(err);
            break;
        case NETWORK_ERR:
            showError("Une erreur de réseau est survenue : Vérifiez votre connexion internet et réessayez. Si le problème persiste, revenez ultérieurement");
            break;
        default:
            showError("Une erreur inconue est survenue : " + error.code);
            break;
    }
};

const rankName = function(rank,playersCount){
    if(rank === 1)
        return PRESIDENT;
    if(rank === 2 && playersCount > 3)
        return VICE_PRESIDENT;
    let nonNeutral = playersCount > 3 ? 2 : 1;
    if((rank > nonNeutral && rank <= (playersCount - nonNeutral)) || rank === 0)
        return NEUTRAL;
    if(rank === (playersCount - 1) && playersCount > 3)
        return VICE_ASSHOLE;
    if(rank === playersCount)
        return ASSHOLE;
};

const translateRankName = function(rankName){
    switch (rankName){
        case PRESIDENT:
            return "Président";
        case VICE_PRESIDENT:
            return "Vice président";
        case NEUTRAL:
            return "Neutre";
        case VICE_ASSHOLE:
            return "Vice trou du cul";
        case ASSHOLE:
            return "Trou du cul";
        default:
            return "Unknown";
    }
};

const compareCards = function(compare,to,revolution){
    //If same
    if(compare === to) return 0;

    //If revolution
    if(revolution){
        switch (compare){
            case 3:
                return 1;
            case 2:
                return -1;
            case 1:
                return to === 2 ? 1 : -1;
            default:
                if(to === 3) return -1;
                if(to === 1 || to === 2) return 1;
                else return compare < to ? 1 : -1;
        }
    }else{
        switch (compare){
            case 2:
                return 1;
            case 1:
                return to === 2 ? -1 : 1;
            default:
                if(to === 1 || to === 2) return -1;
                else return compare > to ? 1 : -1;
        }
    }
};
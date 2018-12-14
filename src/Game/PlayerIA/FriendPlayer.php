<?php

namespace Hackathon\PlayerIA;

use Hackathon\Game\Result;

/**
 * Class LovePlayer
 * @package Hackathon\PlayerIA
 * @author Totokh
 */
class TotokhPlayer extends Player
{
    protected $mySide;
    protected $opponentSide;
    protected $result;

    public function getChoice()
    {
        // -------------------------------------    -----------------------------------------------------
        // How to get my Last Choice           ?    $this->result->getLastChoiceFor($this->mySide) -- if 0 (first round)
        // How to get the opponent Last Choice ?    $this->result->getLastChoiceFor($this->opponentSide) -- if 0 (first round)
        // -------------------------------------    -----------------------------------------------------
        // How to get my Last Score            ?    $this->result->getLastScoreFor($this->mySide) -- if 0 (first round)
        // How to get the opponent Last Score  ?    $this->result->getLastScoreFor($this->opponentSide) -- if 0 (first round)
        // -------------------------------------    -----------------------------------------------------
        // How to get all the Choices          ?    $this->result->getChoicesFor($this->mySide)
        // How to get the opponent Last Choice ?    $this->result->getChoicesFor($this->opponentSide)
        // -------------------------------------    -----------------------------------------------------
        // How to get my Last Score            ?    $this->result->getLastScoreFor($this->mySide)
        // How to get the opponent Last Score  ?    $this->result->getLastScoreFor($this->opponentSide)
        // -------------------------------------    -----------------------------------------------------
        // How to get the stats                ?    $this->result->getStats()
        // How to get the stats for me         ?    $this->result->getStatsFor($this->mySide)
        //          array('name' => value, 'score' => value, 'friend' => value, 'foe' => value
        // How to get the stats for the oppo   ?    $this->result->getStatsFor($this->opponentSide)
        //          array('name' => value, 'score' => value, 'friend' => value, 'foe' => value
        // -------------------------------------    -----------------------------------------------------
        // How to get the number of round      ?    $this->result->getNbRound()
        // -------------------------------------    -----------------------------------------------------
        // How can i display the result of each round ? $this->prettyDisplay()
        // -------------------------------------    -----------------------------------------------------
        $mylast = $this->result->getLastChoiceFor($this->mySide);
        $hislast = $this->result->getLastChoiceFor($this->opponentSide);
        $myscore = $this->result->getLastScoreFor($this->mySide);
        $hisscore = $this->result->getLastScoreFor($this->opponentSide);
        $nb_rnd = $this->result->getNbRound();
        $my_log = $this->result->getChoicesFor($this->mySide);
        $opp_log = $this->result->getChoicesFor($this->opponentSide);
        $stats = $this->result->getStats(); //name friend foe score; number of times N choice is made

        //print($nb_rnd);
        //print_r($my_log);
        //print_r($stats);
        //print($stats["a"]["friend"]);
        //print($stats["a"]["foe"]);

        //first round
        if ($mylast == "0") // for self : <=> $nb_rnd == 0 because nb_rnd starts at 0
            return parent::foeChoice();
        //second round and third round are always friend, because this way I have one more friend than foe most of the
        //times (to win against the strategies of if 1/2+ foe on enemy, I foe)
        if ($nb_rnd == 1 || $nb_rnd == 2)
            return parent::friendChoice();
        //mMst of my behaviour is in this cond. because you cannot define an opponent behaviour before turn 3/4
        if ($nb_rnd > 3) {
            // if he only does friend uptonow, then taking advantage
            if ($stats["a"]["foe"] == 0) //($stats["a"]["friend"] > $stats["a"]["foe"])
                return parent::foeChoice();
            // if he did friend twice in a row, I will friend him.
            if ($hislast == $this->friendChoice() && $opp_log[$nb_rnd - 2] == $this->friendChoice()
                && $opp_log[$nb_rnd - 3] == $this->friendChoice())
                return parent::foeChoice();
            // if he did friend thrice in a row, THEN, I go unfriend to maximize my points
            // (because it means he has a rotating strategy which does friends more than foe)
            if ($hislast == $this->friendChoice() && $opp_log[$nb_rnd - 2] == $this->friendChoice())
                return parent::friendChoice();
        }

        if ($nb_rnd % 2 == 0) //If he did foe recently, then I friend him twice out of three, because
            // I lose nothing this way (unless he is a pigeon but this was detected line 68)
            // Social strategy because most of them are doing if he just did friend, I friend, else I enemy
            return parent::foeChoice();
        else
            return parent::friendChoice();


        /*
           !!!!!!!!!!
        //BELOW is all experiences, it is dead code so that we don't go in
           !!!!!!!!!!
        */

        //do the opposite as him
        if ($hislast == parent::foeChoice())
            return parent::friendChoice();
        else
            return parent::foeChoice();
        //this is a bit suboptimal because it gets only 5 points out of 2 turns in most cases (fr->fo; fo->fr),
        //below the 6 points for collaboration

        //if he is ahead, it means he is trying to get the best of me
        if ($myscore < $hisscore)
            return parent::foeChoice();
        //analysis : it used to work often but with the change of rules it is useless because foe/foe = 0

        //was just friend TWICE
        if ($hislast == $this->friendChoice() && $opp_log[$nb_rnd - 2] == $this->friendChoice())
            return parent::friendChoice();
        //analysis : it worked for points, but was not as good as 1/1/1/1/1, where at worst I lose nothing

        //else just did foe previous and globally hes not worth trusting
        return parent::foeChoice();
    }

};

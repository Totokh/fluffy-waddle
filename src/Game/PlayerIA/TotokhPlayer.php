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

        print($nb_rnd);
        print_r($my_log);
        //print($stats["a"]["friend"]);
        //print($stats["a"]["foe"]);

        //first round
        if ($mylast == "0")
            return parent::friendChoice();
        if ($nb_rnd % 2 == 1)
            return parent::foeChoice(); //At least foe once out of two times
        /* This is for test
        //last round(s)
        //if ($nb_rnd > 8) {
            //return parent::foeChoice();
        //}
        */
        //if he is ahead, it means he is trying to get the best of me
        if ($myscore < $hisscore)
            return parent::foeChoice();

        //was just friend TWICE
        if ($hislast == $this->friendChoice() && $opp_log[$nb_rnd - 2])
            return parent::friendChoice();

        //only does friend uptonow
        if ($stats["a"]["foe"] == 0)//($stats["a"]["friend"] > $stats["a"]["foe"])
            return parent::friendChoice();
        return parent::foeChoice();
    }

};

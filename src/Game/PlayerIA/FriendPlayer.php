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
        //print($stats["a"]["friend"]);
        //print($stats["a"]["foe"]);

        //first round
        if ($mylast == "0" || $nb_rnd == 2)
            return parent::friendChoice();
        //last round
        if ($nb_rnd == 98)
            return parent::foeChoice();

        //only does friend uptonow
        if ($stats["a"]["foe"] == 0 && $nb_rnd > 3) //($stats["a"]["friend"] > $stats["a"]["foe"])
            return parent::foeChoice(); //PIGEON


        if ($nb_rnd % 2 == 1) //AT LEAST FRIEND INCONDITIONALLY ONCE OUT OF TWO to make him trust me,
            // and I lose nothing this way (unless hes a pigeon but this was detected previous line
            // Social strategy because most of them are doing if he just did friend, I friend, else I enemy
            return parent::friendChoice();
        else
            return parent::foeChoice();
        
        /* 
           !!!!!!!!!!
        //BELOW is all experiences, it is dead code so that we don't go in
           !!!!!!!!!!
        */
        
        //if he is ahead, it means he is trying to get the best of me
        if ($myscore < $hisscore)
            return parent::foeChoice();
        //analysis : it worked often but with the change of rules it is useless because foe/foe = 0

        //was just friend TWICE
        if ($hislast == $this->friendChoice() && $opp_log[$nb_rnd - 2] == $this->friendChoice())
            return parent::friendChoice();
        //analysis : it worked for points, but was not as good as 1/1/1/1/1, where at worst I lose nothing
        
        //else just did foe previous and globally hes not worth trusting
        return parent::foeChoice();
    }

};

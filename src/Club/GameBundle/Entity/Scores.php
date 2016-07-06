<?php

namespace Club\GameBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
/**
 * Scores
 *
 * @ORM\Table(name="game_player_scores")
 * @ORM\Entity(repositoryClass="Club\GameBundle\Repository\ScoresRepository")
 */
class Scores
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Matches",
     *     inversedBy="scores"
     * )
     * @ORM\JoinColumn(
     *     name="matches_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL"
     * )
     *
     */
    private $matches;

    /**
     * @ORM\Column(name="num_goals", type="integer")
     */
    private $numGoals = 1;

    /**
     * @var Player
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Player",
     *     inversedBy="scores"
     * )
     * @ORM\JoinColumn(
     *     name="player_id",
     *     referencedColumnName="id"
     * )
     *
     */
    private $player;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numGoals
     *
     * @param integer $numGoals
     *
     * @return Scores
     */
    public function setNumGoals($numGoals)
    {
        $this->numGoals = $numGoals;

        return $this;
    }

    /**
     * Get numGoals
     *
     * @return integer
     */
    public function getNumGoals()
    {
        return $this->numGoals;
    }

    /**
     * Set matches
     *
     * @param Matches $matches
     *
     * @return Scores
     */
    public function setMatches(Matches $matches = null)
    {
        $this->matches = $matches;

        return $this;
    }

    /**
     * Get matches
     *
     * @return Matches
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * Set player
     *
     * @param Player $player
     *
     * @return Scores
     */
    public function setPlayer(Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Get player
     *
     * @return Player
     */
    public function getPlayerName()
    {
        return $this->player->getName();
    }


    public function getPlayerId()
    {
        return $this->player->getId();
    }

    public function getMatchName()
    {
        return $this->matches->getName();
    }

    public function getMatchId()
    {
        return $this->matches->getId();
    }
}

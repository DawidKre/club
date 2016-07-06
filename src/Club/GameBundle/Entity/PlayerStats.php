<?php

namespace Club\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
/**
 * PlayerStats
 *
 * @ORM\Table(name="game_player_stats")
 * @ORM\Entity(repositoryClass="Club\GameBundle\Repository\PlayerStatsRepository")
 */
class PlayerStats
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
     * @var int
     *
     * @ORM\Column(name="matches", type="integer", nullable=true)
     */
    private $matches = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="goals", type="integer")
     */
    private $goals = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="assists", type="integer", nullable=true)
     */
    private $assists = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="red_cards", type="integer", nullable=true)
     */
    private $redCards = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="yellow_cards", type="integer", nullable=true)
     */
    private $yellowCards = 0;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Player",
     *     inversedBy="playerStats"
     * )
     * @ORM\JoinColumn(
     *     name="player_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL"
     * )
     */
    private $player;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Season",
     *     inversedBy="playerStats"
     * )
     * @ORM\JoinColumn(
     *     name="season_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     * )
     */
    private $season;


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
     * Set matches
     *
     * @param integer $matches
     *
     * @return PlayerStats
     */
    public function setMatches($matches)
    {
        $this->matches = $matches;

        return $this;
    }

    /**
     * Get matches
     *
     * @return int
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * Set goals
     *
     * @param integer $goals
     *
     * @return PlayerStats
     */
    public function setGoals($goals)
    {
        $this->goals = $goals;

        return $this;
    }

    /**
     * Get goals
     *
     * @return int
     */
    public function getGoals()
    {
        return $this->goals;
    }

    /**
     * Set assists
     *
     * @param integer $assists
     *
     * @return PlayerStats
     */
    public function setAssists($assists)
    {
        $this->assists = $assists;

        return $this;
    }

    /**
     * Get assists
     *
     * @return int
     */
    public function getAssists()
    {
        return $this->assists;
    }

    /**
     * Set redCards
     *
     * @param integer $redCards
     *
     * @return PlayerStats
     */
    public function setRedCards($redCards)
    {
        $this->redCards = $redCards;

        return $this;
    }

    /**
     * Get redCards
     *
     * @return int
     */
    public function getRedCards()
    {
        return $this->redCards;
    }

    /**
     * Set yellowCards
     *
     * @param integer $yellowCards
     *
     * @return PlayerStats
     */
    public function setYellowCards($yellowCards)
    {
        $this->yellowCards = $yellowCards;

        return $this;
    }

    /**
     * Get yellowCards
     *
     * @return int
     */
    public function getYellowCards()
    {
        return $this->yellowCards;
    }

    /**
     * Set player
     *
     * @param Player $player
     *
     * @return PlayerStats
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
     * Set season
     *
     * @param Season $season
     *
     * @return PlayerStats
     */
    public function setSeason(Season $season = null)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season
     *
     * @return Season
     */
    public function getSeason()
    {
        return $this->season;
    }

    function __toString()
    {
        $player = $this->player->getName();
        return (string)$player;
    }

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

    public function getSeasonName()
    {
        return ($this->season ? $this->season->getName() : null);
    }

    public function getSeasonId()
    {
        return ($this->season ? $this->season->getId() : null);
    }
}

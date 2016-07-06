<?php

namespace Club\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
/**
 * TeamStats
 *
 * @ORM\Table(name="game_team_stats")
 * @ORM\Entity(repositoryClass="Club\GameBundle\Repository\TeamStatsRepository")
 */
class TeamStats
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
     * @ORM\Column(name="wins", type="integer", nullable=true)
     */
    private $wins = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="draws", type="integer", nullable=true)
     */
    private $draws = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="loses", type="integer", nullable=true)
     */
    private $loses = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer", nullable=true)
     */
    private $points = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="goals_scored", type="integer", nullable=true)
     */
    private $goalsScored = 0;
    
    /**
     * @var int
     *
     * @ORM\Column(name="goals_lost", type="integer", nullable=true)
     */
    private $goalsLost = 0;
    /**
     * @var int
     *
     * @ORM\Column(name="bilans", type="integer", nullable=true)
     */
    private $bilans = 0;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Team",
     *     inversedBy="teamStats"
     * )
     * @ORM\JoinColumn(
     *     name="team_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     *
     * )
     */
    private $team;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Season",
     *     inversedBy="teamStats"
     * )
     * @ORM\JoinColumn(
     *     name="season_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL"
     *
     * )
     */
    private $season;

    /**
     * TeamStats constructor.
     *
     */
    public function __construct()
    {
        $drawPoints = intval($this->getDraws());
        $winPoints = intval($this->getWins());

        $goalsScored = intval($this->getGoalsScored());
        $goalsLost = intval($this->getGoalsLost());

        $this->bilans = $goalsScored - $goalsLost;
        $this->points = $drawPoints + $winPoints * 3;
    }


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
     * @return TeamStats
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
     * Set wins
     *
     * @param integer $wins
     *
     * @return TeamStats
     */
    public function setWins($wins)
    {
        $this->wins = $wins;

        return $this;
    }

    /**
     * Get wins
     *
     * @return int
     */
    public function getWins()
    {
        return $this->wins;
    }

    /**
     * Set draws
     *
     * @param integer $draws
     *
     * @return TeamStats
     */
    public function setDraws($draws)
    {
        $this->draws = $draws;

        return $this;
    }

    /**
     * Get draws
     *
     * @return int
     */
    public function getDraws()
    {
        return $this->draws;
    }

    /**
     * Set loses
     *
     * @param integer $loses
     *
     * @return TeamStats
     */
    public function setLoses($loses)
    {
        $this->loses = $loses;

        return $this;
    }

    /**
     * Get loses
     *
     * @return int
     */
    public function getLoses()
    {
        return $this->loses;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return TeamStats
     */
    public function setPoints($points)
    {
        
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }
    
    /**
     * Set bilans
     *
     * @param integer $bilans
     *
     * @return TeamStats
     */
    public function setBilans($bilans)
    {
        $this->bilans = $bilans;

        return $this;
    }

    /**
     * Get bilans
     *
     * @return int
     */
    public function getBilans()
    {
        return $this->bilans;
    }

    /**
     * Set team
     *
     * @param Team $team
     *
     * @return TeamStats
     */
    public function setTeam(Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set season
     *
     * @param Season $season
     *
     * @return TeamStats
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

    /**
     * Set goalsScored
     *
     * @param integer $goalsScored
     *
     * @return TeamStats
     */
    public function setGoalsScored($goalsScored)
    {
        $this->goalsScored = $goalsScored;

        return $this;
    }

    /**
     * Get goalsScored
     *
     * @return integer
     */
    public function getGoalsScored()
    {
        return $this->goalsScored;
    }

    /**
     * Set goalsLost
     *
     * @param integer $goalsLost
     *
     * @return TeamStats
     */
    public function setGoalsLost($goalsLost)
    {
        $this->goalsLost = $goalsLost;

        return $this;
    }

    /**
     * Get goalsLost
     *
     * @return integer
     */
    public function getGoalsLost()
    {
        return $this->goalsLost;
    }

    function __toString()
    {
        $player = $this->season->getName();
        return (string)$player;
    }

    public function getSeasonName()
    {
        return ($this->season ? $this->season->getName() : null);
    }

    public function getSeasonId()
    {
        return ($this->season ? $this->season->getId() : null);
    }

    public function getTeamName()
    {
        return $this->team->getName();
    }

    public function getTeamId()
    {
        return $this->team->getId();
    }
}

<?php

namespace Club\GameBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Season
 *
 * @ORM\Table(name="game_season")
 * @ORM\Entity(repositoryClass="Club\GameBundle\Repository\SeasonRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"name"})
 * @UniqueEntity(fields={"slug"})
 */
class Season
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=120, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(max="120")
     */
    private $name;

    /**
     * @var string
     * @Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=120, unique=true)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;


    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\GameBundle\Entity\PlayerStats",
     *     mappedBy="season",
     *     cascade={"remove"}
     * )
     *
     */
    private $playerStats;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\GameBundle\Entity\TeamStats",
     *     mappedBy="season",
     *     cascade={"remove"}
     * )
     *
     */
    private $teamStats;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\GameBundle\Entity\Matches",
     *     mappedBy="season", 
     * )
     *
     */
    private $matches;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="Club\GameBundle\Entity\Team",
     *     inversedBy="season"
     * )
     * @ORM\JoinTable(name="game_season_teams")
     *
     */
    private $team;

    /**
     * @ORM\Column(type="integer")
     */
    private $teamsNum;


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
     * Set name
     *
     * @param string $name
     *
     * @return Season
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Season
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }


    /**
     * Add playerStat
     *
     * @param PlayerStats $playerStat
     *
     * @return Season
     */
    public function addPlayerStat(PlayerStats $playerStat)
    {
        $this->playerStats[] = $playerStat;

        return $this;
    }

    /**
     * Remove playerStat
     *
     * @param PlayerStats $playerStat
     */
    public function removePlayerStat(PlayerStats $playerStat)
    {
        $this->playerStats->removeElement($playerStat);
    }

    /**
     * Get playerStats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlayerStats()
    {
        return $this->playerStats;
    }

    /**
     * Add teamStat
     *
     * @param TeamStats $teamStat
     *
     * @return Season
     */
    public function addTeamStat(TeamStats $teamStat)
    {
        $this->teamStats[] = $teamStat;

        return $this;
    }

    /**
     * Remove teamStat
     *
     * @param TeamStats $teamStat
     */
    public function removeTeamStat(TeamStats $teamStat)
    {
        $this->teamStats->removeElement($teamStat);
    }

    /**
     * Get teamStats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeamStats()
    {
        return $this->teamStats;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    function __toString()
    {
        return $this->name;
    }

    /**
     * Add match
     *
     * @param Matches $match
     *
     * @return Season
     */
    public function addMatch(Matches $match)
    {
        $this->matches[] = $match;

        return $this;
    }

    /**
     * Remove match
     *
     * @param Matches $match
     */
    public function removeMatch(Matches $match)
    {
        $this->matches->removeElement($match);
    }

    /**
     * Get matches
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * @return mixed
     */
    public function getTeamsNum()
    {
        return $this->teamsNum;
    }

    /**
     * @param mixed $teamsNum
     */
    public function setTeamsNum($teamsNum)
    {
        $this->teamsNum = $teamsNum;
    }


    /**
     * Add team
     *
     * @param Team $team
     *
     * @return Season
     */
    public function addTeam(Team $team)
    {
        $this->team[] = $team;

        return $this;
    }

    /**
     * Remove team
     *
     * @param Team $team
     */
    public function removeTeam(Team $team)
    {
        $this->team->removeElement($team);
    }

    /**
     * Get team
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->playerStats = new \Doctrine\Common\Collections\ArrayCollection();
        $this->teamStats = new \Doctrine\Common\Collections\ArrayCollection();
        $this->matches = new \Doctrine\Common\Collections\ArrayCollection();
        $this->team = new \Doctrine\Common\Collections\ArrayCollection();
    }

}

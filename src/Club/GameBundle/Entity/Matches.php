<?php

namespace Club\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
/**
 * Matches
 *
 * @ORM\Table(name="game_matches")
 * @ORM\Entity(repositoryClass="Club\GameBundle\Repository\MatchesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Matches
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
     * @ORM\Column(name="name", type="string", length=120)
     */
    private $name;

    /**
     * @ORM\Column(name="score", type="string", length=120)
     */
    private $score;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Team",
     *     inversedBy="teamHome"
     * )
     * @ORM\JoinColumn(
     *     name="team_home_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL"
     * )
     */
    private $teamHome;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Team",
     *     inversedBy="teamAway"
     * )
     * @ORM\JoinColumn(
     *     name="team_away_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL"
     * )
     */
    private $teamAway;

    /**
     * @ORM\Column(name="score_for_home", type="integer", nullable=true)
     */
    private $scoreForHome;

    /**
     * @ORM\Column(name="score_for_away", type="integer", nullable=true)
     */
    private $scoreForAway;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\GameBundle\Entity\Scores",
     *     mappedBy="matches"
     * )
     */
    private $scores;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\BlogBundle\Entity\Post",
     *     mappedBy="match"
     * )
     */
    private $post;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Season",
     *     inversedBy="matches"
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
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Round",
     *     inversedBy="matches"
     * )
     * @ORM\JoinColumn(
     *     name="round_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL"
     * )
     */
    private $round;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     * @Assert\NotBlank()
     */
    private $date;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="Club\GameBundle\Entity\Player",
     *     inversedBy="redCards"
     *
     * )
     * @ORM\JoinTable(name="game_players_red_cards")
     *
     */
    private $redCards;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="Club\GameBundle\Entity\Player",
     *     inversedBy="yellowCards"
     *
     * )
     * @ORM\JoinTable(name="game_players_yellow_cards")
     *
     */
    private $yellowCards;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="Club\GameBundle\Entity\Player",
     *     inversedBy="squad"
     *
     * )
     * @ORM\JoinTable(name="game_players_squad")
     *
     */
    private $squad;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\GameBundle\Entity\Assists",
     *     mappedBy="matches"
     * )
     *
     */
    private $assists;


    function __toString()
    {
        return $this->name;
    }


    /**
     * @ORM\PrePersist
     * @ORM\PostUpdate()
     */
    public function preSave()
    {
        $this->setName($this->teamHome->getName() . ' - ' .
            $this->teamAway->getName() . '    ' .
            date_format($this->date, "d/m/Y"));

        $this->setScore($this->scoreForHome . ' : ' . $this->scoreForAway);
    }

    /**
     * Get id
     *
     * @return integer
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
     * @return Matches
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
     * Set score
     *
     * @param string $score
     *
     * @return Matches
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return string
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set scoreForHome
     *
     * @param integer $scoreForHome
     *
     * @return Matches
     */
    public function setScoreForHome($scoreForHome)
    {
        $this->scoreForHome = $scoreForHome;

        return $this;
    }

    /**
     * Get scoreForHome
     *
     * @return integer
     */
    public function getScoreForHome()
    {
        return $this->scoreForHome;
    }

    /**
     * Set scoreForAway
     *
     * @param integer $scoreForAway
     *
     * @return Matches
     */
    public function setScoreForAway($scoreForAway)
    {
        $this->scoreForAway = $scoreForAway;

        return $this;
    }

    /**
     * Get scoreForAway
     *
     * @return integer
     */
    public function getScoreForAway()
    {
        return $this->scoreForAway;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Matches
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set teamHome
     *
     * @param \Club\GameBundle\Entity\Team $teamHome
     *
     * @return Matches
     */
    public function setTeamHome(\Club\GameBundle\Entity\Team $teamHome = null)
    {
        $this->teamHome = $teamHome;

        return $this;
    }

    /**
     * Get teamHome
     *
     * @return \Club\GameBundle\Entity\Team
     */
    public function getTeamHome()
    {
        return $this->teamHome;
    }

    /**
     * Set teamAway
     *
     * @param \Club\GameBundle\Entity\Team $teamAway
     *
     * @return Matches
     */
    public function setTeamAway(\Club\GameBundle\Entity\Team $teamAway = null)
    {
        $this->teamAway = $teamAway;

        return $this;
    }

    /**
     * Get teamAway
     *
     * @return \Club\GameBundle\Entity\Team
     */
    public function getTeamAway()
    {
        return $this->teamAway;
    }


    /**
     * Add score
     *
     * @param \Club\GameBundle\Entity\Scores $score
     *
     * @return Matches
     */
    public function addScore(\Club\GameBundle\Entity\Scores $score)
    {
        $this->scores[] = $score;

        return $this;
    }

    /**
     * Remove score
     *
     * @param \Club\GameBundle\Entity\Scores $score
     */
    public function removeScore(\Club\GameBundle\Entity\Scores $score)
    {
        $this->scores->removeElement($score);
    }

    /**
     * Get scores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScores()
    {
        return $this->scores;
    }

    /**
     * Set season
     *
     * @param \Club\GameBundle\Entity\Season $season
     *
     * @return Matches
     */
    public function setSeason(\Club\GameBundle\Entity\Season $season = null)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season
     *
     * @return \Club\GameBundle\Entity\Season
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set round
     *
     * @param \Club\GameBundle\Entity\Round $round
     *
     * @return Matches
     */
    public function setRound(\Club\GameBundle\Entity\Round $round = null)
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Get round
     *
     * @return \Club\GameBundle\Entity\Round
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * Add redCard
     *
     * @param \Club\GameBundle\Entity\Player $redCard
     *
     * @return Matches
     */
    public function addRedCard(\Club\GameBundle\Entity\Player $redCard)
    {
        $this->redCards[] = $redCard;

        return $this;
    }

    /**
     * Remove redCard
     *
     * @param \Club\GameBundle\Entity\Player $redCard
     */
    public function removeRedCard(\Club\GameBundle\Entity\Player $redCard)
    {
        $this->redCards->removeElement($redCard);
    }

    /**
     * Get redCards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRedCards()
    {
        return $this->redCards;
    }

    /**
     * Add yellowCard
     *
     * @param \Club\GameBundle\Entity\Player $yellowCard
     *
     * @return Matches
     */
    public function addYellowCard(\Club\GameBundle\Entity\Player $yellowCard)
    {
        $this->yellowCards[] = $yellowCard;

        return $this;
    }

    /**
     * Remove yellowCard
     *
     * @param \Club\GameBundle\Entity\Player $yellowCard
     */
    public function removeYellowCard(\Club\GameBundle\Entity\Player $yellowCard)
    {
        $this->yellowCards->removeElement($yellowCard);
    }

    /**
     * Get yellowCards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getYellowCards()
    {
        return $this->yellowCards;
    }

    /**
     * Add squad
     *
     * @param \Club\GameBundle\Entity\Player $squad
     *
     * @return Matches
     */
    public function addSquad(\Club\GameBundle\Entity\Player $squad)
    {
        $this->squad[] = $squad;

        return $this;
    }

    /**
     * Remove squad
     *
     * @param \Club\GameBundle\Entity\Player $squad
     */
    public function removeSquad(\Club\GameBundle\Entity\Player $squad)
    {
        $this->squad->removeElement($squad);
    }

    /**
     * Get squad
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSquad()
    {
        return $this->squad;
    }

    /**
     * Add assist
     *
     * @param \Club\GameBundle\Entity\Assists $assist
     *
     * @return Matches
     */
    public function addAssist(\Club\GameBundle\Entity\Assists $assist)
    {
        $this->assists[] = $assist;

        return $this;
    }

    /**
     * Remove assist
     *
     * @param \Club\GameBundle\Entity\Assists $assist
     */
    public function removeAssist(\Club\GameBundle\Entity\Assists $assist)
    {
        $this->assists->removeElement($assist);
    }

    /**
     * Get assists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssists()
    {
        return $this->assists;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->scores = new \Doctrine\Common\Collections\ArrayCollection();
        $this->post = new \Doctrine\Common\Collections\ArrayCollection();
        $this->redCards = new \Doctrine\Common\Collections\ArrayCollection();
        $this->yellowCards = new \Doctrine\Common\Collections\ArrayCollection();
        $this->squad = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assists = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add post
     *
     * @param \Club\BlogBundle\Entity\Post $post
     *
     * @return Matches
     */
    public function addPost(\Club\BlogBundle\Entity\Post $post)
    {
        $this->post[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Club\BlogBundle\Entity\Post $post
     */
    public function removePost(\Club\BlogBundle\Entity\Post $post)
    {
        $this->post->removeElement($post);
    }

    /**
     * Get post
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPost()
    {
        return $this->post;
    }
//    public function getPostName()
//    {
//        return ($this->post ? $this->post->getTitle() : null);
//    }

//    public function getPostId()
//    {
//        return ($this->post ? $this->post->getId() : null);
//    }

    public function getTeamHomeName()
    {
        return $this->teamHome->getName();
    }

    public function getTeamHomeId()
    {
        return $this->teamHome->getId();
    }

    public function getTeamAwayName()
    {
        return $this->teamAway->getName();
    }

    public function getTeamAwayId()
    {
        return $this->teamAway->getId();
    }

    public function getSeasonName()
    {
        return ($this->season ? $this->season->getName() : null);
    }

    public function getSeasonId()
    {
        return ($this->season ? $this->season->getId() : null);
    }

    public function getRoundName()
    {
        return ($this->round ? $this->round->getName() : null);
    }

    public function getRoundId()
    {
        return ($this->round ? $this->round->getId() : null);
    }

}

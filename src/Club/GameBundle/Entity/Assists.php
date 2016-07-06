<?php

namespace Club\GameBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
/**
 * 
 *
 * @ORM\Table(name="game_player_assists")
 * @ORM\Entity(repositoryClass="Club\GameBundle\Repository\AssistsRepository")
 */
class Assists
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
     *     inversedBy="assists"
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
     * @ORM\Column(name="num_assists", type="integer")
     */
    private $numAssists = 1;

    /**
     *
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Player",
     *     inversedBy="assists"
     * )
     * @ORM\JoinColumn(
     *     name="player_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL"
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
     * @return mixed
     */
    public function getNumAssists()
    {
        return $this->numAssists;
    }

    /**
     * @param mixed $numAssists
     */
    public function setNumAssists($numAssists)
    {
        $this->numAssists = $numAssists;
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

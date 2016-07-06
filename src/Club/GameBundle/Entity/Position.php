<?php

namespace Club\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
/**
 * Position
 *
 * @ORM\Table(name="game_position")
 * @ORM\Entity(repositoryClass="Club\GameBundle\Repository\PositionRepository")
 * @UniqueEntity(fields={"name", "slug"})
 */
class Position
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
     * @Assert\Length(max="120")
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @Slug(fields={"name"})
     *
     * @ORM\Column(name="slug", type="string", length=100, unique=true)
     * @Assert\Length(max="120")
     */
    private $slug;

    /**
     * @var int
     *
     * @ORM\Column(name="position_order", type="integer", nullable=true, length=10)
     *
     */
    private $positionOrder;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\GameBundle\Entity\Player",
     *     mappedBy="position"
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
     * Set name
     *
     * @param string $name
     *
     * @return Position
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
     * @return Position
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
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preSave()
    {
        if (null === $this->slug) {
            $this->setSlug($this->getName());
        }
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->player = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add player
     *
     * @param Player $player
     *
     * @return Position
     */
    public function addPlayer(Player $player)
    {
        $this->player[] = $player;

        return $this;
    }

    /**
     * Remove player
     *
     * @param Player $player
     */
    public function removePlayer(Player $player)
    {
        $this->player->removeElement($player);
    }

    /**
     * Get player
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set positionOrder
     *
     * @param integer $positionOrder
     *
     * @return Position
     */
    public function setPositionOrder($positionOrder)
    {
        $this->positionOrder = $positionOrder;

        return $this;
    }

    /**
     * Get positionOrder
     *
     * @return integer
     */
    public function getPositionOrder()
    {
        return $this->positionOrder;
    }

    function __toString()
    {
        return $this->name;
    }
}

<?php

namespace Club\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Player
 *
 * @ORM\Table(name="game_player")
 * @ORM\Entity(repositoryClass="Club\GameBundle\Repository\PlayerRepository")
 *
 * @UniqueEntity(fields={"name"})
 * @UniqueEntity(fields={"slug"})
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Player
{

    const DEFAULT_PHOTO = 'default-photo.png';
    const UPLOAD_DIR = 'uploads/players/';
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
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(min="5",max="100")
     */
    private $name;

    /**
     * @var string
     *
     * @Slug(fields={"name"})
     *
     * @ORM\Column(name="slug", type="string", length=100, unique=true)
     * @Assert\Length(max="100")
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="born_date", type="datetime", nullable=true)
     */
    private $bornDate;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer", nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="string", name="photo", length=100, nullable=true)
     */
    private $photo;

    /**
     * @var UploadedFile
     *
     * @Assert\Image(
     *      minWidth = 50,
     *      maxWidth = 250,
     *      minHeight = 50,
     *      maxHeight = 250,
     *      maxSize = "2M",
     *      groups = {"ChangeDetails"}
     * )
     */
    private $photoFile;

    private $photoTemp;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Team",
     *     inversedBy="player"
     * )
     * @ORM\JoinColumn(
     *     name="team_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL"
     * )
     */
    private $team;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Position",
     *     inversedBy="player"
     * )
     * @ORM\JoinColumn(
     *     name="position_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL",
     *     nullable=true
     * )
     * @ORM\OrderBy({"positionOrder" = "DESC"})
     * 
     */
    private $position;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\GameBundle\Entity\PlayerStats",
     *     mappedBy="player"
     * )
     *
     */
    private $playerStats;

    /**
     * @ORM\Column(type="datetime", nullable = true, name="update_date")
     */
    private $updateDate = null;


    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\GameBundle\Entity\Scores",
     *     mappedBy="player"
     * )
     *
     */
    private $scores;

    /**
     * @ORM\ManyToMany(targetEntity="Club\GameBundle\Entity\Matches",
     *      mappedBy="redCards"
     * )
     *
     */
    private $redCards;

    /**
     * @ORM\ManyToMany(targetEntity="Club\GameBundle\Entity\Matches",
     *      mappedBy="yellowCards"
     * )
     *
     */
    private $yellowCards;

    /**
     * @ORM\ManyToMany(targetEntity="Club\GameBundle\Entity\Matches",
     *      mappedBy="squad"
     * )
     *
     */
    private $squad;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\GameBundle\Entity\Assists",
     *     mappedBy="player"
     * )
     *
     */
    private $assists;

    function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     *
     * @return Player
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
     * Set position
     *
     * @param string $position
     *
     * @return Player
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set bornDate
     *
     * @param \DateTime $bornDate
     *
     * @return Player
     */
    public function setBornDate($bornDate)
    {
        $this->bornDate = $bornDate;

        return $this;
    }

    /**
     * Get bornDate
     *
     * @return \DateTime
     */
    public function getBornDate()
    {
        return $this->bornDate;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return Player
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    public function setPhotoFile(UploadedFile $photoFile)
    {
        $this->photoFile = $photoFile;
        $this->updateDate = new \DateTime();
        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        if (null == $this->photo) {
            return Player::UPLOAD_DIR . Player::DEFAULT_PHOTO;
        }

        return Player::UPLOAD_DIR . $this->photo;
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

        if (null !== $this->getPhotoFile()) {

            if (null !== $this->photo) {
                $this->photoTemp = $this->photo;
            }

            $fileName = sha1(uniqid(null, true));
            $this->photo = $fileName . '.' . $this->getPhotoFile()->guessExtension();
        }

    }

    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function postSave()
    {
        if (NULL !== $this->getphotoFile()) {
            $this->getphotoFile()->move($this->getUploadRootDir(), $this->photo);
            unset($this->photoFile);

            if (isset($this->photoTemp)) {
                unlink($this->getUploadRootDir() . '/' . $this->photoTemp);
                unset($this->photoTemp);
            }
        }
    }

    /**
     * @ORM\PostRemove
     */
    public function postRemove()
    {
        if (null !== $this->photo) {
            unlink($this->getUploadRootDir() . '/' . $this->photo);
        }
    }

    public function getUploadRootDir()
    {
        return __DIR__ . '/../../../../web/' . self::UPLOAD_DIR;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Player
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
     * Set photo
     *
     * @param string $photo
     *
     * @return Player
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Player
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }


    /**
     * Set team
     *
     * @param \Club\GameBundle\Entity\Team $team
     *
     * @return Player
     */
    public function setTeam(\Club\GameBundle\Entity\Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return \Club\GameBundle\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Add playerStat
     *
     * @param \Club\GameBundle\Entity\PlayerStats $playerStat
     *
     * @return Player
     */
    public function addPlayerStat(\Club\GameBundle\Entity\PlayerStats $playerStat)
    {
        $this->playerStats[] = $playerStat;

        return $this;
    }

    /**
     * Remove playerStat
     *
     * @param \Club\GameBundle\Entity\PlayerStats $playerStat
     */
    public function removePlayerStat(\Club\GameBundle\Entity\PlayerStats $playerStat)
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
     * Add score
     *
     * @param \Club\GameBundle\Entity\Scores $score
     *
     * @return Player
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
     * Add redCard
     *
     * @param \Club\GameBundle\Entity\Matches $redCard
     *
     * @return Player
     */
    public function addRedCard(\Club\GameBundle\Entity\Matches $redCard)
    {
        $this->redCards[] = $redCard;

        return $this;
    }

    /**
     * Remove redCard
     *
     * @param \Club\GameBundle\Entity\Matches $redCard
     */
    public function removeRedCard(\Club\GameBundle\Entity\Matches $redCard)
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
     * @param \Club\GameBundle\Entity\Matches $yellowCard
     *
     * @return Player
     */
    public function addYellowCard(\Club\GameBundle\Entity\Matches $yellowCard)
    {
        $this->yellowCards[] = $yellowCard;

        return $this;
    }

    /**
     * Remove yellowCard
     *
     * @param \Club\GameBundle\Entity\Matches $yellowCard
     */
    public function removeYellowCard(\Club\GameBundle\Entity\Matches $yellowCard)
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
     * @param \Club\GameBundle\Entity\Matches $squad
     *
     * @return Player
     */
    public function addSquad(\Club\GameBundle\Entity\Matches $squad)
    {
        $this->squad[] = $squad;

        return $this;
    }

    /**
     * Remove squad
     *
     * @param \Club\GameBundle\Entity\Matches $squad
     */
    public function removeSquad(\Club\GameBundle\Entity\Matches $squad)
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
     * Constructor
     */
    public function __construct()
    {
        $this->playerStats = new \Doctrine\Common\Collections\ArrayCollection();
        $this->scores = new \Doctrine\Common\Collections\ArrayCollection();
        $this->redCards = new \Doctrine\Common\Collections\ArrayCollection();
        $this->yellowCards = new \Doctrine\Common\Collections\ArrayCollection();
        $this->squad = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assists = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add assist
     *
     * @param \Club\GameBundle\Entity\Assists $assist
     *
     * @return Player
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

    public function getTeamName()
    {
        return ($this->team ? $this->team->getName() : null);
    }

    public function getTeamId()
    {
        return ($this->team ? $this->team->getId() : null);
    }

    public function getPositionName()
    {
        return ($this->position ? $this->position->getName() : null);
    }

    public function getPositionId()
    {
        return ($this->position ? $this->position->getId() : null);
    }

}

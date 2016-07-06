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
 * Team
 *
 * @ORM\Table(name="game_team")
 * @ORM\Entity(repositoryClass="Club\GameBundle\Repository\TeamRepository")
 *
 * @UniqueEntity(fields={"name"})
 * @UniqueEntity(fields={"slug"})
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Team
{
    const DEFAULT_CREST = 'default-crest.png';
    const UPLOAD_DIR = 'uploads/crests/';
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
     * @Assert\Length(min="5", max="120")
     */
    private $name;

    /**
     * @var string
     * @Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=120, unique=true)
     * @Assert\Length(max="100")
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="adress", type="string", length=255, nullable=true)
     */
    private $adress = null;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", length=255, nullable=true)
     */
    private $about;

    /**
     * @var string
     *
     * @ORM\Column(name="colors", type="string", length=120, nullable=true)
     * @Assert\Length(max="120")
     */
    private $colors;

    /**
     * @var int
     *
     * @ORM\Column(name="phone", type="integer", nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=120, nullable=true, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="crest", type="string", length=80, nullable=true)
     */
    private $crest;

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
    private $crestFile;

    private $crestTemp;

    /**
     * @ORM\Column(type="datetime", nullable = true, name="creation_date")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     *
     */
    private $facebookLink;

    /**
     * @ORM\Column(type="datetime", nullable = true, name="update_date")
     */
    private $updateDate = null;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\GameBundle\Entity\Player",
     *     mappedBy="team"
     * )
     *
     */
    private $player;
    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\GameBundle\Entity\Matches",
     *     mappedBy="teamHome"
     * )
     *
     */
    private $teamHome;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\GameBundle\Entity\Matches",
     *     mappedBy="teamAway"
     * )
     *
     */
    private $teamAway;
    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\GameBundle\Entity\TeamStats",
     *     mappedBy="team"
     * )
     *
     */
    private $teamStats;

    /**
     * @ORM\ManyToMany(targetEntity="Club\GameBundle\Entity\Season",
     *      mappedBy="team"
     * )
     *
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
     * Set name
     *
     * @param string $name
     *
     * @return Team
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
     * @return Team
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
     * Set adress
     *
     * @param string $adress
     *
     * @return Team
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set about
     *
     * @param string $about
     *
     * @return Team
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set colors
     *
     * @param string $colors
     *
     * @return Team
     */
    public function setColors($colors)
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * Get colors
     *
     * @return string
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return Team
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Team
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set crest
     *
     * @param string $crest
     *
     * @return Team
     */
    public function setCrest($crest)
    {
        $this->crest = $crest;

        return $this;
    }


    public function getCrestFile()
    {
        return $this->crestFile;
    }

    public function setCrestFile(UploadedFile $crestFile)
    {
        $this->crestFile = $crestFile;
        $this->updateDate = new \DateTime();
        return $this;
    }

    /**
     * Get crest
     *
     * @return string
     */
    public function getCrest()
    {
        if (null == $this->crest) {
            return Team::UPLOAD_DIR . Team::DEFAULT_CREST;
        }

        return Team::UPLOAD_DIR . $this->crest;
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

        if (null !== $this->getCrestFile()) {
            if (null !== $this->crest) {
                $this->crestTemp = $this->crest;
            }

            $fileName = sha1(uniqid(null, true));
            $this->crest = $fileName . '.' . $this->getCrestFile()->guessExtension();
        }

    }

    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function postSave()
    {
        if (NULL !== $this->getCrestFile()) {
            $this->getCrestFile()->move($this->getUploadRootDir(), $this->crest);
            unset($this->crestFile);

            if (isset($this->crestTemp)) {
                unlink($this->getUploadRootDir() . '/' . $this->crestTemp);
                unset($this->crestTemp);
            }
        }
    }

    /**
     * @ORM\PostRemove
     */
    public function postRemove()
    {
        if (null !== $this->crest) {
            unlink($this->getUploadRootDir() . '/' . $this->crest);
        }
    }

    public function getUploadRootDir()
    {
        return __DIR__ . '/../../../../web/' . self::UPLOAD_DIR;
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Team
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set facebookLink
     *
     * @param string $facebookLink
     *
     * @return Team
     */
    public function setFacebookLink($facebookLink)
    {
        $this->facebookLink = $facebookLink;

        return $this;
    }

    /**
     * Get facebookLink
     *
     * @return string
     */
    public function getFacebookLink()
    {
        return $this->facebookLink;
    }

    /**
     * Add teamStat
     *
     * @param TeamStats $teamStat
     *
     * @return Team
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

    function __toString()
    {
        return $this->name;
    }

    /**
     * Add teamHome
     *
     * @param Matches $teamHome
     *
     * @return Team
     */
    public function addTeamHome(Matches $teamHome)
    {
        $this->teamHome[] = $teamHome;

        return $this;
    }

    /**
     * Remove teamHome
     *
     * @param Matches $teamHome
     */
    public function removeTeamHome(Matches $teamHome)
    {
        $this->teamHome->removeElement($teamHome);
    }

    /**
     * Get teamHome
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeamHome()
    {
        return $this->teamHome;
    }

    /**
     * Add teamAway
     *
     * @param Matches $teamAway
     *
     * @return Team
     */
    public function addTeamAway(Matches $teamAway)
    {
        $this->teamAway[] = $teamAway;

        return $this;
    }

    /**
     * Remove teamAway
     *
     * @param Matches $teamAway
     */
    public function removeTeamAway(Matches $teamAway)
    {
        $this->teamAway->removeElement($teamAway);
    }

    /**
     * Get teamAway
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeamAway()
    {
        return $this->teamAway;
    }

    /**
     * Add season
     *
     * @param Season $season
     *
     * @return Team
     */
    public function addSeason(Season $season)
    {
        $this->season[] = $season;

        return $this;
    }

    /**
     * Remove season
     *
     * @param Season $season
     */
    public function removeSeason(Season $season)
    {
        $this->season->removeElement($season);
    }

    /**
     * Get season
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->player = new \Doctrine\Common\Collections\ArrayCollection();
        $this->teamHome = new \Doctrine\Common\Collections\ArrayCollection();
        $this->teamAway = new \Doctrine\Common\Collections\ArrayCollection();
        $this->teamStats = new \Doctrine\Common\Collections\ArrayCollection();
        $this->season = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add player
     *
     * @param Player $player
     *
     * @return Team
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
}

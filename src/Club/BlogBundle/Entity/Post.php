<?php

namespace Club\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Post
 * @Serializer\ExclusionPolicy("all")
 * @ORM\Table(name="blog_post")
 * @ORM\Entity(repositoryClass="Club\BlogBundle\Repository\PostRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @UniqueEntity(fields={"title", "slug"}, message="Ta nazwa jest już zajęta")
 * @ORM\HasLifecycleCallbacks()
 *  * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *          "api_blog_post_show",
 *          parameters={"slug" = "expr(object.getSlug())"}
 *      )
 * )
 * @Hateoas\Relation(
 *     "category",
 *     href=@Hateoas\Route(
 *          "api_blog_categories_show",
 *          parameters={"slug" = "expr(object.getCategorySlug())"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "comments",
 *     href = @Hateoas\Route(
 *          "api_blog_posts_comments_list",
 *          parameters={"slug" = "expr(object.getSlug())"}
 *      )
 * )
 */
class Post
{
    const DEFAULT_THUMBNAIL = 'default3.gif';
    const UPLOAD_DIR = 'uploads/thumbnails/';

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
     * @ORM\Column(name="title", type="string", length=120, unique=true)
     * @Assert\NotBlank(message="Nazwa postu nie może być pusta")
     * @Assert\Length(max="120")
     * @Serializer\Expose()
     */
    private $title;

    /**
     * @var string
     *
     * @Slug(fields={"title"})
     *
     * @ORM\Column(name="post_slug", type="string", length=120, unique=true)
     * @Assert\Length(max="120")
     *
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     * @Serializer\Expose()
     */
    private $content;

    /**
     * @var string
     * @Serializer\Expose()
     * @ORM\Column(name="thumbnail", type="string", length=80, nullable=true)
     */
    private $thumbnail;

    /**
     * @var UploadedFile
     * @Assert\Image(
     *      minWidth = 60,
     *      minHeight = 48,
     *      maxWidth = 1920,
     *      maxHeight = 1080,
     *      maxSize = "2M"
     * )
     */
    private $thumbnailFile;

    private $thumbnailTemp;

    /**
     *
     * @var boolean
     * @ORM\Column(
     *     type="boolean",
     *     nullable=true
     *
     * )
     * @Serializer\Expose()
     */
    private $isMatch = true;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Club\GameBundle\Entity\Matches",
     *     inversedBy="post"
     *
     * )
     * @ORM\JoinColumn(
     *     name="matches_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL",
     *     nullable=true
     * )
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private $match;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Club\UserBundle\Entity\User"
     * )
     *
     * @ORM\JoinColumn(
     *     name="author_id",
     *     referencedColumnName="id",
     *
     *
     * )
     *
     * 
     */
    private $author;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Club\BlogBundle\Entity\Category",
     *     inversedBy="posts",
     *     cascade={"persist"}
     *
     * )
     * @ORM\JoinColumn(
     *     name="category_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL"
     * )
     * 
     */
    private $category;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     * @Serializer\Expose()
     */
    private $createDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_date", type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $publishedDate;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\BlogBundle\Entity\Comment",
     *     mappedBy="post",
     *      cascade={"remove"}
     * )
     *
     * @ORM\OrderBy({"createDate" = "DESC"})
     *
     */
    private $comments;

    /**
     * @var boolean
     * @ORM\Column(name="is_commentable", type="boolean", nullable=true)
     */
    private $isCommentable = true;

    /**
     * @ORM\Column(name="num_comments", type="integer", nullable=true)
     */
    private $numComments = 0;

    /**
     * @var \DateTime $deletedAt
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    public function __construct()
    {
        $this->isMatch = true;
        $this->comments = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Post
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
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     *
     * @return Post
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getThumbnailFile()
    {
        return $this->thumbnailFile;
    }

    /**
     * @param UploadedFile $thumbnailFile
     * @return $this
     */
    public function setThumbnailFile(UploadedFile $thumbnailFile)
    {
        $this->thumbnailFile = $thumbnailFile;
        $this->updateDate = new \DateTime();
        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        if (null == $this->thumbnail) {
            return Post::UPLOAD_DIR . Post::DEFAULT_THUMBNAIL;
        }

        return Post::UPLOAD_DIR . $this->thumbnail;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Post
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }


    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Post
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set publishedDate
     *
     * @param \DateTime $publishedDate
     *
     * @return Post
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    /**
     * Get publishedDate
     *
     * @return \DateTime
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }


    /**
     * Set category
     *
     * @param Category|string $category
     * @return Post
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preSave()
    {
        if (null === $this->slug) {
            $this->setSlug($this->getTitle());
        }
        if (null !== $this->getThumbnailFile()) {
            if(null !== $this->thumbnail){
                $this->thumbnailTemp = $this->thumbnail;
            }
            $fileName = sha1(uniqid(null, true));
            $this->thumbnail = $fileName.'.'.$this->getThumbnailFile()->guessExtension();
        }

        if (null == $this->createDate) {
            $this->createDate = new \DateTime();
        }
        if (null == $this->publishedDate) {
            $this->publishedDate = new \DateTime();
        }
    }

    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     * 
     */
    public function postSave()
    {
        if (NULL !== $this->getThumbnailFile()) {
            $this->getThumbnailFile()->move($this->getUploadRootDir(), $this->thumbnail);
            unset($this->thumbnailFile);

            if (isset($this->thumbnailTemp)) {
                unlink($this->getUploadRootDir() . '/' . $this->thumbnailTemp);
                unset($this->thumbnailTemp);
            }
        }
    }

    /**
     * @ORM\PostRemove
     */
    public function postRemove()
    {
        if (null !== $this->thumbnail) {
            unlink($this->getUploadRootDir() . '/' . $this->thumbnail);
        }
    }

    public function getUploadRootDir()
    {
        return __DIR__ . '/../../../../web/' . self::UPLOAD_DIR;
    }

    /**
     * @return mixed
     */
    public function getIsMatch()
    {
        return $this->isMatch;
    }

    /**
     * @param mixed $isMatch
     */
    public function setIsMatch($isMatch)
    {
        $this->isMatch = $isMatch;
    }


    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Post
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
     * Add comment
     *
     * @param Comment $comment
     *
     * @return Post
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set isCommentable
     *
     * @param boolean $isCommentable
     *
     * @return Post
     */
    public function setIsCommentable($isCommentable)
    {
        $this->isCommentable = $isCommentable;

        return $this;
    }

    /**
     * Get isCommentable
     *
     * @return boolean
     */
    public function getIsCommentable()
    {
        return $this->isCommentable;
    }

    /**
     * Set numComments
     *
     * @param integer $numComments
     *
     * @return Post
     */
    public function setNumComments($numComments)
    {
        $this->numComments = intval($numComments);
        
    }

    /**
     * Get numComments
     *
     * @return integer
     */
    public function getNumComments()
    {
        return $this->numComments;
    }


    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Post
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    function __toString()
    {
        return $this->title;
    }


    /**
     * Set match
     *
     * @param \Club\GameBundle\Entity\Matches $match
     *
     * @return Post
     */
    public function setMatch(\Club\GameBundle\Entity\Matches $match = null)
    {
        $this->match = $match;

        return $this;
    }

    /**
     * Get match
     *
     * @return \Club\GameBundle\Entity\Matches
     */
    public function getMatch()
    {
        return $this->match;
    }

    public function getCategorySlug()
    {
        return $this->category->getSlug();
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("category")
     */
    public function getCategoryName()
    {
        return $this->category->getName();
    }


}

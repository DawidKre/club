<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 20.04.16
 * Time: 09:52
 */

namespace Club\BlogBundle\Entity;

use Club\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_comments")
 * @ORM\Entity(repositoryClass="Club\BlogBundle\Repository\CommentRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Serializer\ExclusionPolicy("all")
 * 
 */
class Comment
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Club\BlogBundle\Entity\Post",
     *     inversedBy="comments"
     * )
     *
     * @ORM\JoinColumn(
     *     name="post_id",
     *     referencedColumnName="id",
     *     nullable= false,
     *     onDelete="CASCADE"
     * )
     */
    private $post;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Club\UserBundle\Entity\User"
     * )
     *
     * @ORM\JoinColumn(
     *     name="author_id",
     *     referencedColumnName="id",
     *     nullable= true,
     *     onDelete="SET NULL"
     * )
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Expose()
     */
    private $createDate;

    /**
     * @ORM\Column(type="text", length=1000, nullable=true)
     * @Assert\NotBlank()
     *
     * @Assert\Length(max="1000")
     * @Serializer\Expose()
     */
    private $comment;

    /**
     * @var boolean
     * @ORM\Column(
     *     type="boolean", nullable=true
     *   
     * )
     */
    private $isDeleted = false;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotBlank(),
     * @Assert\Length(max="100")
     * @Serializer\Expose()
     */
    private $user;


    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Assert\Email()
     * @Assert\Length(
     *     max="120"
     * )
     * @Serializer\Expose()
     */
    private $email;

    /**
     * @var \DateTime $deletedAt
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Comment
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

    /**
     * Comment constructor.
     */
    public function __construct()
    {
        $this->createDate = new \DateTime();
        $this->isDeleted = false;
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
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Comment
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
     * Set post
     *
     * @param Post $post
     *
     * @return Comment
     */
    public function setPost(Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set author
     *
     * @param User $author
     *
     * @return Comment
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

//    /**
//     * Set depth
//     *
//     * @param integer $depth
//     *
//     * @return Comment
//     */
//    public function setDepth($depth)
//    {
//        $this->depth = $depth;
//
//        return $this;
//    }
//
//    /**
//     * Get depth
//     *
//     * @return integer
//     */
//    public function getDepth()
//    {
//        return $this->depth;
//    }
//
//
//    /**
//     * Set ancestor
//     *
//     * @param string $ancestor
//     *
//     * @return Comment
//     */
//    public function setAncestor($ancestor)
//    {
//        $this->ancestor = $ancestor;
//
//        return $this;
//    }
//
//    /**
//     * Get ancestor
//     *
//     * @return string
//     */
//    public function getAncestor()
//    {
//        return $this->ancestor;
//    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Comment
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return Comment
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Comment
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
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("post")
     */
    public function getPostName()
    {
        return $this->post->getTitle();
    }
//    /**
//     * @Serializer\VirtualProperty()
//     * @Serializer\SerializedName("avatar")
//     */
//    public function getUserAvatar()
//    {
//        return $this->author->getAvatar();
//    }

}

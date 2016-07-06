<?php

namespace Club\BlogBundle\Entity;

use Club\BlogBundle\Annotation\Links;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Category
 * @Serializer\ExclusionPolicy("all")
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ORM\Table(name="blog_category")
 * @ORM\Entity(repositoryClass="Club\BlogBundle\Repository\CategoryRepository")
 * @UniqueEntity(fields={"name", "slug"}, message="Ta nazwa jest już zajęta")
 * @Links(
 *     "self1",
 *     route = "api_blog_categories_show",
 *     params = { "slug":"object.getSlug()" }
 * )
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *          "api_blog_categories_show",
 *          parameters={"slug" = "expr(object.getSlug())"}
 *      )
 * )
 * @Hateoas\Relation(
 *     "posts",
 *     href = @Hateoas\Route(
 *          "api_blog_categories_posts_list",
 *          parameters={"slug" = "expr(object.getSlug())"}
 *      )
 * )
 *
 * 
 */
class Category
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
     * @Serializer\Expose()
     * @ORM\Column(name="name", type="string", length=120, unique=true)
     * @Assert\NotBlank(message="Nazwa kategorii nie może być pusta")
     */
    private $name;

    /**
     * @var string
     * @Gedmo\Slug(fields={"name"})
     * @Serializer\Expose()
     * @ORM\Column(name="category_slug", type="string", length=120, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Club\BlogBundle\Entity\Post",
     *     mappedBy="category"
     * )
     */
    public $posts;

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
     * @return Category
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
     * @return Category
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
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add post
     *
     * @param \Club\BlogBundle\Entity\Post $post
     *
     * @return Category
     */
    public function addPost(\Club\BlogBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Club\BlogBundle\Entity\Post $post
     */
    public function removePost(\Club\BlogBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    function __toString()
    {

        return $this->name;
    }
}

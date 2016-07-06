<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 31.05.16
 * Time: 10:46
 */

namespace Club\BlogBundle\Form\Model;

use Club\BlogBundle\Entity\Post;
use Symfony\Component\Validator\Constraints as Assert;

class CommentModel
{
    /**
     * @Assert\NotBlank()
     */
    private $post;

    /**
     * @Assert\NotBlank()
     */
    private $comment;

    private $email;

    private $user;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


}
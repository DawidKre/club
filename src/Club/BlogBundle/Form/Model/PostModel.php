<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 31.05.16
 * Time: 10:46
 */

namespace Club\BlogBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class PostModel
{
    /**
     * @Assert\NotBlank()
     */
    private $category;

    /**
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @Assert\NotBlank()
     */
    private $title;


    private $isMatch;

    public function getCategory()
    {
        return $this->category;
    }


    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }


    public function setContent($content)
    {
        $this->content = $content;
    }


    public function getTitle()
    {
        return $this->title;
    }


    public function setTitle($title)
    {
        $this->title = $title;
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


}
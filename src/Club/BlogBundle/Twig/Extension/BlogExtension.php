<?php

namespace Club\BlogBundle\Twig\Extension;

use Club\BlogBundle\Entity\Comment;
use Club\BlogBundle\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Twig_Environment;

class BlogExtension extends \Twig_Extension implements \Twig_Extension_InitRuntimeInterface
{
    /**
     * @var Registry
     */
    private $doctrine;
    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * BlogExtension constructor.
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'club_blog_extension';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('print_latest_news_comments', array($this, 'latestNewsComments'), array('is_safe'=>array('html'))),
        );
    }

    public function initRuntime(Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function latestNewsComments($limit = 7)
    {

        $CommentRepo = $this->doctrine->getRepository(Comment::class);
        $CommentList = $CommentRepo->getLatestComments($limit);

        $PostRepo = $this->doctrine->getRepository(Post::class);
        $PostList = $PostRepo->getLatestNews($limit);

        return $this->environment->render('@ClubBlog/Template/newsCommentsWidget.html.twig', array(
            'commentList' => $CommentList,
            'postsList' => $PostList
        ));
    }
    
    
        
    
}

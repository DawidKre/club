<?php

namespace Club\BlogBundle\Controller;


use Club\BlogBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CategoryController extends BaseController
{

    /**
     * @param $slug
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/category/{slug}/{page}",
     *     name="blog_category_list",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     */
    public function listAction($slug, $page)
    {
        $pagination = $this->getPaginationList(array(
            'categorySlug' => $slug
        ), $page, $slug, Category::class);

        
        return $this->render('ClubBlogBundle:Category:list.html.twig', array(
            'pagination' => $pagination['pagination'],
            'siteTitle' => sprintf('Posty w kategorii: "%s"', $pagination['qb']->getName()),
            'title' => $pagination['qb']->getName()
        ));
    }


}

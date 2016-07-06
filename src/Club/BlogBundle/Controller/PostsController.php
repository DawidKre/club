<?php

namespace Club\BlogBundle\Controller;

use Club\BlogBundle\Entity\Comment;
use Club\BlogBundle\Form\CommentType;
use Club\UserBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class PostsController extends BaseController
{
    /**
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{page}",
     *     name="blog_posts_index",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     */
    public function indexAction($page)
    {
        $qb = $this->getPostRepository()->findAll();
        $pagination = $this->getPaginationPost(array(), $page);

        return $this->render('ClubBlogBundle:Posts:index.html.twig', array(
            'pagination' => $pagination,
            'siteTitle' => 'Aktualności'
        ));
    }

    /**
     * @Route("/search/{page}",
     *     name="blog_post_search",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request, $page)
    {
        $searchParam = $request->query->get('search');
        $pagination = $this->getPaginationPost(
            array(
                'search' => $searchParam,
            ),
            $page
        );

        return $this->render(
            'ClubBlogBundle:Posts:searchList.html.twig',
            array(
                'pagination' => $pagination,
                'siteTitle' => sprintf('Wyniki wyszukiwania frazy "%s', $searchParam),
                'title' => $searchParam,
            )
        );
    }

    /**
     * @param Request $request
     * @param $slug
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param Request $request
     * @internal param Post $post
     * @Route(
     *     "/{slug}/{page}",
     *     name="blog_posts_post",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     */
    public function postAction(Request $request, $slug, $page)
    {
        $post = $this->getPostRepository()->findOneBySlug($slug);
        if (null === $post) {
            throw $this->createPostNotFoundHttpException($slug);
        }


        //Comment section
        $Comment = new Comment();

        if (null !== $this->getUser()) {
            $Comment->setAuthor($this->getUser())
                ->setPost($post)
                ->setUser($this->getUser()->getUsername())
                ->setEmail($this->getUser()->getEmail()
                );
            $commentForm = $this->createForm(CommentType::class, $Comment);

        } else {
            $Comment->setPost($post);
            $commentForm = $this->createForm(CommentType::class, $Comment);
        }

        if ($request->isMethod('POST')) {
            $commentForm->handleRequest($request);

            if ($commentForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($Comment);
                $em->flush();

                $this->addFlash('success', ' Komentarz dodany');
                $redirectUrl = $this->generateUrl(
                    'blog_posts_post',
                    array(
                        'slug' => $post->getSlug(),
                    )
                );

                return $this->redirect($redirectUrl);
            }
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($post->getComments(), $page, 5);

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $csrfProvider = $this->get('security.csrf.token_manager');
        }
        return $this->render('ClubBlogBundle:Posts:post.html.twig', array(
            'post' => $post,
            'pagination' => $pagination,
            'commentForm' => isset($commentForm) ? $commentForm->createView() : null,
            'csrfProvider' => isset($csrfProvider) ? $csrfProvider : null,
            'tokenName' => 'delCom%d',
        ));
    }

    /**
     * @ApiDoc(
     *     description=" Comment delete",
     *     input= {
     *          "class" = "Club\BlogBundle\Form\CommentType",
     *          "options"   = {"method" = "POST"},
     *          "name"      = ""
     *     },
     *     method="POST",
     *     statusCodes={}
     *     
     * )
     * @Route(
     *     "/post/comment/delete/{commentId}/{token}",
     *     name="blog_deleteComment"
     * )
     * @param $commentId
     * @param $token
     * @return JsonResponse
     */
    public function deleteCommentAction($commentId, $token)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException('Nie masz uprawnień do tego zadania');
        }
        $validToken = sprintf('delCom%d', $commentId);

        if (!$this->isCsrfTokenValid($validToken, $token)) {
            throw $this->createAccessDeniedException('Błędny token akcji');
        }

        $Comment = $this->getDoctrine()->getRepository(Comment::class)->find($commentId);

        if (null == $Comment) {
            throw $this->createCommentNotFoundHttpException($commentId);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($Comment);
        $em->flush();
      

        return new JsonResponse(
            array(
                'status' => 'ok',
                'message' => 'Komentarz został usunięty',
            )
        );
    }

    /**
     * @param $username
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/author/{username}/{page}",
     *     name="blog_author_list",
     *     defaults={"page" = 1},
     *     requirements={"page" = "\d+"}
     * )
     */
    public function authorListAction($username, $page)
    {
        $UserRepo = $this->getDoctrine()->getRepository(User::class);
        $User = $UserRepo->findOneByUsername($username);

        if (null === $User) {
            throw $this->createAuthorNotFoundHttpException($username);
        }

        $pagination = $this->getPaginationPost(
            array(
                'status' => 'published',
                'orderBy' => 'p.publishedDate',
                'orderDir' => 'DESC',
                'username' => $User,
            ),
            $page
        );

        return $this->render('ClubBlogBundle:Category:list.html.twig',
            array(
                'pagination' => $pagination,
                'siteTitle' => sprintf('Wpisy autora: "%s"', $username),
                'title' => $username
            )
        );
    }

}

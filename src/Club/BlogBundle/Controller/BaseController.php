<?php

namespace Club\BlogBundle\Controller;

use Club\BlogBundle\Api\ApiProblem;
use Club\BlogBundle\Api\ApiProblemException;
use Club\BlogBundle\Entity\Category;
use Club\BlogBundle\Entity\Comment;
use Club\BlogBundle\Entity\Post;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{

    protected function createPostNotFoundHttpException($slug)
    {
        return new NotFoundHttpException(sprintf(
            'Post with slug "%s" was not found',
            $slug
        ));
    }

    protected function createCommentNotFoundHttpException($commentId)
    {
        return new NotFoundHttpException(sprintf(
            'Comment with id "%d" was not found',
            $commentId
        ));
    }
    protected function createAuthorNotFoundHttpException($author)
    {
        return new NotFoundHttpException(sprintf(
            'Author name "%s" was not found',
            $author
        ));
    }
    protected function createSearchNotFoundException($slug)
    {
        return new NotFoundHttpException(sprintf(
            ' "%s" was not found',
            $slug
        ));
    }
    
    protected function getPaginationList(array $params = array(), $page, $slug, $repo)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository($repo);
        $slug = $repository->findOneBySlug($slug);

        if (null === $slug) {
            throw $this->createSearchNotFoundException($slug);
        }
        $pagination = $this->getPaginationPost($params, $page);

        return array(
            'pagination' => $pagination,
            'qb' => $slug
        );
    }

    protected function getPaginationPost(array $params = array('status' => 'published'), $page)
    {
        $PostRepo = $this->getPostRepository();
        $qb = $PostRepo->getQueryBuilder($params);

        $paginator = $this->get('knp_paginator');
        $pageRange = $this->getParameter('post_per_page');
        $pagination = $paginator->paginate($qb, $page, $pageRange);

        return $pagination;
    }
    
    public function getCategoryRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Category::class);
    }

    public function getPostRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Post::class);
    }

    public function getCommentRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Comment::class);
    }

    public function serialize($data)
    {
        $context = new SerializationContext();
        
        $context->setSerializeNull(true);
        $context->enableMaxDepthChecks();
        
        return $this->get('jms_serializer')
            ->serialize($data, 'json', $context);
    }

    public function createApiResponse($data, $statusCode = 200)
    {
        $json = $this->serialize($data);
        return new Response($json, $statusCode,[
            'Content-Type' => 'application/hal+json'
        ]);
    }

    /**
     * @param $form
     */
    public function throwApiProblemValidationException(FormInterface $form)
    {
        $errors = $this->getErrorsFromForm($form);
        $apiProblem = new ApiProblem(400,
            ApiProblem::TYPE_VALIDATION_ERROR
        );
        $apiProblem->set('errors', $errors);

        throw new ApiProblemException($apiProblem);

    }

    public function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

    public function processForm(Request $request, FormInterface $form)
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        if (null === $data) {
            $apiProblem = new ApiProblem(
                400,
                ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT
            );
            throw new ApiProblemException($apiProblem);

        }

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

}

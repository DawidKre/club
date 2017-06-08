<?php

namespace Club\BlogBundle\Controller\Api;

use Club\BlogBundle\Controller\BaseController;
use Club\BlogBundle\Entity\Comment;
use Club\BlogBundle\Entity\Post;
use Club\BlogBundle\Form\Api\CommentType;
use Club\BlogBundle\Form\Api\PostType;
use Club\BlogBundle\Form\Api\UpdatePostType;
use Club\BlogBundle\Form\Model\PostModel;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends BaseController
{
    /**
     * Creates a new Post
     *
     * @ApiDoc(
     *     input="Club\BlogBundle\Form\Type\Api\PostType",
     *     output="Club\BlogBundle\Entity\Post",
     *     statusCodes={
    201="Returned when a new Post has been successfully created",
     *          400="Returned when the posted data is invalid"
     *     }
     * )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param $name
     * @Route("/api/posts")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $postModel = new PostModel();
        $form = $this->createForm(PostType::class, $postModel);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $post = new Post();

        $categoryRepo = $this->getCategoryRepository();
        $category = $categoryRepo->findOneByName($postModel->getCategory());

        if (!$category) {
            throw $this->createNotFoundException(sprintf(
                'No category found with name "%s"',
                $postModel->getCategory()
            ));
        }

        $post->setAuthor($this->getUser());
        $post->setTitle($postModel->getTitle());
        $post->setCategory($category);
        $post->setContent($postModel->getContent());
        $post->setIsMatch($postModel->getIsMatch());
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        $location = $this->generateUrl('api_blog_post_show', [
            'slug' => $post->getSlug()
        ]);

        $response = $this->createApiResponse($post, 201);
        $response->headers->set('Location', $location);

        return $response;
    }

    /**
     *
     * Gets a single Post.
     *
     * @ApiDoc(
     *     output="Club\BlogBundle\Entity\Post",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Route("/api/posts/{slug}",
     *     name="api_blog_post_show"
     * )
     * @Method("GET")
     * @param $slug
     * @return JsonResponse
     */
    public function showAction($slug)
    {
        $post = $this->getPostRepository()
            ->findOneBySlug($slug);

        if (!$post) {
            throw $this->createNotFoundException('No post found');
        }
        $response = $this->createApiResponse($post);

        return $response;
    }

    /**
     * Gets a collection of the given Posts.
     *
     * @ApiDoc(
     *     output="Club\BlogBundle\Entity\Post",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Route("/api/posts",
     *     name="api_blog_posts_collection"
     * )
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $filter = $request->query->get('filter');
        $qb = $this->getPostRepository()
            ->findAllQueryBuilder($filter);

        $paginatedCollection = $this->get('pagination_factory')
            ->createCollection($qb, $request, 'api_blog_posts_collection');

        $response = $this->createApiResponse($paginatedCollection, 200);

        return $response;
    }

    /**
     * Update Post
     *
     * @ApiDoc(
     *     resource= true,
     *     input="Club\BlogBundle\Form\Type\Api\CategoryType",
     *     output="Club\BlogBundle\Entity\Category",
     *     statusCodes={
     *          200="Returned when successful",
     *          400="Returned when errors",
     *          404="Returned when not found"
     *     }
     * )
     * @Route("/api/posts/{slug}",
     *     name="api_blog_posts_update"
     * )
     * @Method({"PUT", "PATCH"})
     * @param $slug
     * @param Request $request
     * @return JsonResponse*
     * @internal param $name
     */
    public function updateAction($slug, Request $request)
    {
        $post = $this->getPostRepository()
            ->findOneBySlug($slug);

        if (!$post) {
            throw $this->createNotFoundException(sprintf(
                'No post found with slug "%s"',
                $slug
            ));
        }

        $form = $this->createForm(UpdatePostType::class, $post);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        $response = $this->createApiResponse($post, 200);
        return $response;
    }

    /**
     * Delete a specific Post by slug
     *
     * @ApiDoc(
     *      description="Deletes an existing Post",
     *      statusCodes={
     *         204="Returned when an existing Post has been successfully deleted",
     *         403="Returned when trying to delete a non existent Category"
     *     }
     * )
     * @Route("/api/posts/{slug}",
     *     name="api_blog_posts_delete"
     * )
     * @Method("DELETE")
     * @param $slug
     * @return JsonResponse*
     * @internal param $name
     */
    public function deleteAction($slug)
    {
        $post = $this->getPostRepository()
            ->findOneBySlug($slug);

        if ($post) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
        }

        return new Response(null, 204);
    }

    /**
     * Gets a collection of the Comments by specific Post
     *
     * @ApiDoc(
     *     output="Club\BlogBundle\Entity\Post",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Route("/api/posts/{slug}/comments",
     *     name="api_blog_posts_comments_list"
     * )
     * @Method("GET")
     * @param Post $post
     * @param Request $request
     * @return Response
     * @internal param Comment $comment
     * @internal param Category $category
     * @internal param Request $request
     */
    public function commentsListAction(Post $post, Request $request)
    {
        $commentsQb = $this->getCommentRepository()
            ->createQueryBuilderForPosts($post);

        $collection = $this->get('pagination_factory')->createCollection(
            $commentsQb,
            $request,
            'api_blog_posts_comments_list',
            ['slug' => $post->getSlug()]
        );
        return $this->createApiResponse($collection);
    }

    /**
     * Creates a new Comment for specific Post
     *
     * @ApiDoc(
     *     input="Club\BlogBundle\Form\Type\Api\CommentType",
     *     output="Club\BlogBundle\Entity\Comment",
     *     statusCodes={
     *          201="Returned when a new Comment has been successfully created",
     *          400="Returned when the posted data is invalid"
     *     }
     * )
     * @Route("/api/posts/{slug}/comments")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commentsNewAction(Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $this->processForm($request, $form);
        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        $response = $this->createApiResponse($comment, 201);
        return $response;
    }
}

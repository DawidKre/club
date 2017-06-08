<?php

namespace Club\BlogBundle\Controller\Api;

use Club\BlogBundle\Controller\BaseController;
use Club\BlogBundle\Entity\Category;
use Club\BlogBundle\Form\Api\CategoryType;
use Club\BlogBundle\Form\Api\UpdateCategoryType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class CategoryController extends BaseController
{
    /**
     * Creates a new Category
     *
     * @ApiDoc(
     *     input="Club\BlogBundle\Form\Type\Api\CategoryType",
     *     output="Club\BlogBundle\Entity\Category",
     *     statusCodes={
     *          201="Returned when a new Category has been successfully created",
     *          400="Returned when the posted data is invalid"
     *     }
     * )
     * @Route("api/categories")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        $location = $this->generateUrl('api_blog_categories_show', [
            'slug' => $category->getSlug()
        ]);

        $response = $this->createApiResponse($category, 201);
        $response->headers->set('Location', $location);

        return $response;
    }

    /**
     * Gets a single Category.
     *
     * @ApiDoc(
     *     output="Club\BlogBundle\Entity\Category",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     *
     * @Route("/api/categories/{slug}",
     *     name="api_blog_categories_show"
     * )
     * @Method("GET")
     * @param $slug
     * @return JsonResponse
     */
    public function showAction($slug)
    {
        $category = $this->getCategoryRepository()
            ->findOneBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException('No category found');
        }
        $response = $this->createApiResponse($category);

        return $response;
    }

    /**
     * Gets a collection of the given Categories.
     *
     * @ApiDoc(
     *     output="Club\BlogBundle\Entity\Category",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Route("/api/categories",
     *     name="api_blog_categories_collection"
     * )
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $filter = $request->query->get('filter');
        $qb = $this->getCategoryRepository()
            ->findAllQueryBuilder($filter);

        $paginatedCollection = $this->get('pagination_factory')
            ->createCollection($qb, $request, 'api_blog_categories_collection');

        $response = $this->createApiResponse($paginatedCollection, 200);
        
        return $response;
    }

    /**
     * Update Category
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
     * @Route("/api/categories/{slug}",
     *     name="api_blog_categories_update"
     * )
     * @Method({"PUT", "PATCH"})
     * @param $slug
     * @param Request $request
     * @return JsonResponse*
     * @internal param $name
     */
    public function updateAction($slug, Request $request)
    {

        $category = $this->getCategoryRepository()
            ->findOneBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException(sprintf(
                'No category found with slug "%s"',
                $slug
            ));
        }

        $form = $this->createForm(UpdateCategoryType::class, $category, [
            'is_edit' => false
        ]);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        $response = $this->createApiResponse($category, 200);
        return $response;

    }

    /**
     * Delete a specific Category by slug
     *
     * @ApiDoc(
     *      description="Deletes an existing Category",
     *      statusCodes={
     *         204="Returned when an existing Category has been successfully deleted",
     *         403="Returned when trying to delete a non existent Category"
     *     }
     * )
     * @Route("/api/categories/{slug}",
     *     name="api_blog_categories_delete"
     * )
     * @Method("DELETE")
     * @param $slug
     * @return JsonResponse*
     * @internal param $name
     */
    public function deleteAction($slug)
    {
        $category = $this->getCategoryRepository()
            ->findOneBySlug($slug);

        if ($category) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return new jsonResponse(null, 204);


    }

    /**
     * Gets a collection of the posts by specific Category
     *
     * @ApiDoc(
     *     output="Club\BlogBundle\Entity\Category",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Route("/api/categories/{slug}/posts",
     *     name="api_blog_categories_posts_list"
     * )
     * @Method("GET")
     * @param Category $category
     * @param Request $request
     * @return Response
     * @internal param Request $request
     */
    public function postsListAction(Category $category, Request $request)
    {
        $postsQb = $this->getPostRepository()
            ->createQueryBuilderForCategory($category);

        $collection = $this->get('pagination_factory')->createCollection(
            $postsQb,
            $request,
            'api_blog_categories_posts_list',
            ['slug' => $category->getSlug()]
        );
        return $this->createApiResponse($collection);
    }
}
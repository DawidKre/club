<?php

namespace Club\BlogBundle\Controller\Api;

use Club\BlogBundle\Controller\BaseController;
use Club\BlogBundle\Entity\Comment;
use Club\BlogBundle\Form\Api\CommentType;
use Club\BlogBundle\Form\Api\UpdateCommentType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

///**
// *
// * @Security("is_granted('ROLE_USER')")
// */
class CommentController extends BaseController
{
    /**
     * Creates a new Comment
     *
     * @ApiDoc(
     *     input="Club\BlogBundle\Form\Type\Api\CommentType",
     *     output="Club\BlogBundle\Entity\Comment",
     *     statusCodes={
     *          201="Returned when a new Comment has been successfully created",
     *          400="Returned when the posted data is invalid"
     *     }
     * )
     * @Route("/api/comments")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $commentModel = new Comment();
        $form = $this->createForm(CommentType::class, $commentModel);
        $this->processForm($request, $form);
        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($commentModel);
        $em->flush();

        $response = $this->createApiResponse($commentModel, 201);
        return $response;
    }

    /**
     * Delete a specific Comment by Id
     *
     * @ApiDoc(
     *      description="Deletes an existing Comment",
     *      statusCodes={
     *         204="Returned when an existing Comment has been successfully deleted",
     *         403="Returned when trying to delete a non existent Comment"
     *     }
     * )
     * @Route("/api/comments/{id}",
     *     name="api_blog_comments_delete"
     * )
     * @Method("DELETE")
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction($id)
    {
        $comment = $this->getCommentRepository()
            ->findOneById($id);

        if ($comment) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
        }
        return new Response(null, 204);
    }

    /**
     * Update Comment
     *
     * @ApiDoc(
     *     resource= true,
     *     input="Club\BlogBundle\Form\Type\Api\UpdateCommentType",
     *     output="Club\BlogBundle\Entity\Comment",
     *     statusCodes={
     *          200="Returned when successful",
     *          400="Returned when errors",
     *          404="Returned when not found"
     *     }
     * )
     * @Route("/api/comments/{id}",
     *     name="api_blog_comments_update",
     *     requirements={"id" = "\d+"}
     * )
     * @Method({"PUT", "PATCH"})
     * @param $id
     * @param Request $request
     * @return JsonResponse ** @internal param $name
     */
    public function updateAction($id, Request $request)
    {
        $comment = $this->getCommentRepository()
            ->findOneById($id);

        if (!$comment) {
            throw $this->createNotFoundException(sprintf(
                'No comment found with id "%s"',
                $id
            ));
        }

        $form = $this->createForm(UpdateCommentType::class, $comment, [
            'is_edit' => true
        ]);

        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        $response = $this->createApiResponse($comment, 200);
        return $response;

    }

}

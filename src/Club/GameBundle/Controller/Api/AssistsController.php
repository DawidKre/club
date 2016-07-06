<?php

namespace Club\GameBundle\Controller\Api;

use Club\GameBundle\Controller\BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;


class AssistsController extends BaseController
{

    /**
     * Gets a collection of the given Assists.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Assists",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_assists_list", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View()
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getAssistsAction(Request $request)
    {
        $repo = $this->getAssistsRepository();
        $collection = $this->apiBaseGET($request, $repo, 'api_game_assists_list');
        return $collection;
    }


    /**
     * Gets a single Assist.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Assists",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     *
     * @Rest\Get(name="api_game_assist_show", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View(serializerGroups={"Details", "Default","list"})
     * @param $id
     * @return null|object
     */
    public function getAssistAction($id)
    {
        $resource = $this->getAssistsRepository()
            ->find($id);

        if (!$resource) {
            throw $this->createNotFoundException('Data not found.');
        }
        return $resource;
    }

}

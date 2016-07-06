<?php

namespace Club\GameBundle\Controller\Api;


use Club\GameBundle\Controller\BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;


class SeasonController extends BaseController
{

    /**
     * Gets a collection of the given Season.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Season",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_seasons_list", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View(serializerGroups={"Default"})
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getSeasonsAction(Request $request)
    {
        $repo = $this->getSeasonRepository();
        $collection = $this->apiBaseGET($request, $repo, 'api_game_seasons_list');
        return $collection;
    }


    /**
     * Gets a single Season.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Season",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_season_show", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View(serializerGroups={"Details", "Default","list"})
     * @param $id
     * @return
     */
    public function getSeasonAction($id)
    {
        $collection = $this->getSeasonRepository()
            ->findOneById($id);

        if (!$collection) {
            throw $this->createNotFoundException('Data not found.');
        }
        return $collection;
    }

}

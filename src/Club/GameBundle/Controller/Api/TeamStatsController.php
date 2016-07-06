<?php

namespace Club\GameBundle\Controller\Api;


use Club\GameBundle\Controller\BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;


class TeamStatsController extends BaseController
{

    /**
     * Gets a collection of the given TeamStats.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\TeamStats",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(
     *     path="team-stats",
     *     name="api_game_team_stats_list",
     *     options={ 
     *     "method_prefix" = false }
     * )
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View()
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getTeamsStatsAction(Request $request)
    {
        $repo = $this->getTeamStatsRepository();
        $collection = $this->apiBaseGET($request, $repo, 'api_game_team_stats_list');
        return $collection;
    }


    /**
     * Gets a single Team Stats.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\TeamStats",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(
     *     path="team-stats/{id}",
     *     name="api_game_team_stats_show",
     *     options={ "method_prefix" = false },
     *     requirements={"id" = "\d+"}
     *
     * )
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View(serializerGroups={"Details", "Default","list"})
     * @param $id
     * @return null|object
     */
    public function getTeamStatsAction($id)
    {
        $resource = $this->getTeamStatsRepository()->find($id);

        if (!$resource) {
            throw $this->createNotFoundException('Data not found.');
        }
        return $resource;
    }

}

<?php

namespace Club\GameBundle\Controller\Api;

use Club\GameBundle\Controller\BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PlayerStatsController
 * @package Club\GameBundle\Controller\Api
 */
class PlayerStatsController extends BaseController
{

    /**
     * Gets a collection of the given PlayerStats.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\PlayerStats",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(path="player-stats",name="api_game_player_stats_list", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View()
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getPlayersStatsAction(Request $request)
    {
        $repo = $this->getPlayerStatsRepository();
        $collection = $this->apiBaseGET($request, $repo, 'api_game_player_stats_list');
        return $collection;
    }


    /**
     * Gets a single Player Stats.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\PlayerStats",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(
     *     path="player-stats/{id}",
     *     name="api_game_player_stats_show",
     *     options={ "method_prefix" = false }
     *
     * )
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View(serializerGroups={"Details", "Default","list"})
     * @param $id
     * @return null|object
     */
    public function getPlayerStatsAction($id)
    {
        $assist = $this->getPlayerStatsRepository()
            ->find($id);

        if (!$assist) {
            throw $this->createNotFoundException('Data not found.');
        }
        return $assist;
    }

}

<?php

namespace Club\GameBundle\Controller\Api;


use Club\GameBundle\Controller\BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;


class PositionController extends BaseController
{

    /**
     * Gets a collection of the given Positions.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Position",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_positions_list", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View()
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getPositionsAction(Request $request)
    {
        $positionRepo = $this->getPositionRepository();
        $qb = $positionRepo->findAll();
        if (!$qb) {
            throw $this->createNotFoundException('Data not found.');
        }
        $paginatedCollection = $this->get('pagination_factory')
            ->createCollection($qb, $request, 'api_game_positions_list');

        return $paginatedCollection;
    }

    /**
     * Gets a single Postion.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Position",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_position_show", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View(serializerGroups={"Details", "Default","list"})
     * @param $slug
     * @return
     */
    public function getPositionAction($slug)
    {
        $resource = $this->getPositionRepository()
            ->findOneBySlug($slug);

        if (!$resource) {
            throw $this->createNotFoundException('Data not found.');
        }
        return $resource;
    }


    /**
     * Return the overall user list.
     * @ApiDoc(
     *   resource = true,
     *   description = "Return the overall Players List",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     * @Rest\Get(name="api_game_positions_players_list", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View()
     * @param $slug
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getPositionPlayersAction($slug, Request $request)
    {
        $positionQb = $this->getPlayerRepository()
            ->createQueryBuilderForPosition($slug);

        if (!$positionQb) {
            throw $this->createNotFoundException('Data not found.');
        }

        $collection = $this->get('pagination_factory')->createCollection(
            $positionQb,
            $request,
            'api_game_positions_players_list',
            ['slug' => $slug]
        );

        return $collection;
    }

}

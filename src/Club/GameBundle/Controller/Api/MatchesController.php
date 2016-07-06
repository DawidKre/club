<?php

namespace Club\GameBundle\Controller\Api;


use Club\GameBundle\Controller\BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;


class MatchesController extends BaseController
{

    /**
     * Gets a collection of the given Matches.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Matches",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_matches_list", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json"})
     * @Rest\View(serializerGroups={"Default"})
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getMatchesAction(Request $request)
    {
        $repo = $this->getMatchesRepository();
        $collection = $this->apiBaseGET($request, $repo, 'api_game_matches_list');
        return $collection;
    }


    /**
     * Gets a single Match.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Matches",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_match_show", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View(serializerGroups={"Details", "Default","list"})
     * @param $id
     * @return
     */
    public function getMatchAction($id)
    {
        $collection = $this->getMatchesRepository()
            ->findOneById($id);

        if (!$collection) {
            throw $this->createNotFoundException('Data not found.');
        }
        return $collection;
    }

    /**
     * Gets a collection of the Assists by specific Match
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Assists",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_match_assists_list", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View()
     * @param $id
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getMatchAssistsAction($id, Request $request)
    {
        $qb = $this->getAssistsRepository()
            ->createQueryBuilderForMatch($id);

        if (!$qb) {
            throw $this->createNotFoundException('Data not found.');
        }

        $collection = $this->get('pagination_factory')->createCollection(
            $qb,
            $request,
            'api_game_match_assists_list',
            ['id' => $id]
        );

        return $collection;
    }

    /**
     * Gets a collection of the Squad by specific Match
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Matches",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_match_squad_list", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View()
     * @param $id
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getMatchSquadAction($id, Request $request)
    {
        $qb = $this->getMatchesRepository()
            ->createQueryBuilderForSquad($id);

        if (!$qb) {
            throw $this->createNotFoundException('Data not found.');
        }

        $collection = $this->get('pagination_factory')->createCollection(
            $qb,
            $request,
            'api_game_match_squad_list',
            ['id' => $id]
        );

        return $collection;
    }
}

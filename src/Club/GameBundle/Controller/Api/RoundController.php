<?php

namespace Club\GameBundle\Controller\Api;


use Club\GameBundle\Controller\BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;


class RoundController extends BaseController
{

    /**
     * Gets a collection of the given Rounds.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Round",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_rounds_list", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View(serializerGroups={"Default"})
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getRoundsAction(Request $request)
    {
        $repo = $this->getRoundRepository();
        $collection = $this->apiBaseGET($request, $repo, 'api_game_rounds_list');
        return $collection;
    }


    /**
     * Gets a single Round.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Rouns",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_round_show", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View(serializerGroups={"Details", "Default","list"})
     * @param $id
     * @return
     */
    public function getRoundAction($id)
    {
        $collection = $this->getRoundRepository()
            ->findOneById($id);

        if (!$collection) {
            throw $this->createNotFoundException('Data not found.');
        }
        return $collection;
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
     * @Rest\Get(name="api_game_round_matches_list", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View()
     * @param $id
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getRoundMatchesAction($id, Request $request)
    {
        $roundQb = $this->getMatchesRepository()
            ->createQueryBuilderForRound($id);

        if (!$roundQb) {
            throw $this->createNotFoundException('Data not found.');
        }

        $collection = $this->get('pagination_factory')->createCollection(
            $roundQb,
            $request,
            'api_game_round_matches_list',
            ['id' => $id]
        );

        return $collection;
    }

}

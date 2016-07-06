<?php

namespace Club\GameBundle\Controller\Api;

use Club\BlogBundle\Api\ApiProblem;
use Club\BlogBundle\Api\ApiProblemException;
use Club\GameBundle\Controller\BaseController;
use Club\GameBundle\Entity\Assists;
use Club\GameBundle\Entity\Player;
use Club\GameBundle\Entity\Position;
use Club\GameBundle\Entity\Team;
use Club\GameBundle\Form\Api\PlayerType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;


class ScoresController extends BaseController
{

    /**
     * Gets a collection of the given Scores.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Scores",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_scores_list", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View()
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getScoresAction(Request $request)
    {
        $repo = $this->getScoresRepository();
        $collection = $this->apiBaseGET($request, $repo, 'api_game_scores_list');
        return $collection;
    }


    /**
     * Gets a single Scores.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Scores",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_score_show", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View(serializerGroups={"Details", "Default","list"})
     * @param $id
     * @return null|object
     */
    public function getScoreAction($id)
    {
        $assist = $this->getScoresRepository()
            ->find($id);

        if (!$assist) {
            throw $this->createNotFoundException('Data not found.');
        }
        return $assist;
    }

}

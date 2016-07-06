<?php

namespace Club\GameBundle\Controller\Api;


use Club\GameBundle\Controller\BaseController;
use Club\GameBundle\Entity\Team;
use Club\GameBundle\Form\Api\TeamType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;


class TeamController extends BaseController
{
    /**
     * Gets a collection of the given Teams.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Team",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @param Team $team
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function postTeamAction(Team $team, Request $request)
    {
        $form = $this->createForm(TeamType::class, $team);

        $controller = $this->get('club_blog_base_controller');
        $controller->processForm($request, $form);

        if (!$form->isValid()) {
            $controller->throwApiProblemValidationException($form);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($team);
        $em->flush();

        $location = $this->generateUrl('api_game_team_show', [
            'slug' => $team->getSlug()
        ]);

        return $this->view($team, 201, ['Location' => $location]);
    }


    /**
     * Gets a single Team.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Team",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_teams_list", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View()
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getTeamsAction(Request $request)
    {
        $teamRepo = $this->getTeamRepository();
        $qb = $teamRepo->findAll();
        if (!$qb) {
            throw $this->createNotFoundException('Data not found.');
        }
        $paginatedCollection = $this->get('pagination_factory')
            ->createCollection($qb, $request, 'api_game_teams_list');

        return $paginatedCollection;
    }

    /**
     * Return the overall user list.
     *
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Return the overall Players List",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     * @Rest\Get(name="api_game_team_show", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View(serializerGroups={"Details", "Default","list"})
     * @param $slug
     * @return
     */
    public function getTeamAction($slug)
    {
        $player = $this->getTeamRepository()
            ->findOneBySlug($slug);

        if (!$player) {
            throw $this->createNotFoundException('Data not found.');
        }
        return $player;
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
     * @Rest\Get(name="api_game_team_players_list", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View()
     * @param $slug
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getTeamPlayersAction($slug, Request $request)
    {
        $positionQb = $this->getPlayerRepository()
            ->createQueryBuilderForTeam($slug);

        if (!$positionQb) {
            throw $this->createNotFoundException('Data not found.');
        }

        $collection = $this->get('pagination_factory')->createCollection(
            $positionQb,
            $request,
            'api_game_team_players_list',
            ['slug' => $slug]
        );

        return $collection;
    }

}

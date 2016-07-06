<?php

namespace Club\GameBundle\Controller\Api;

use Club\BlogBundle\Api\ApiProblem;
use Club\BlogBundle\Api\ApiProblemException;
use Club\GameBundle\Controller\BaseController;
use Club\GameBundle\Entity\Player;
use Club\GameBundle\Form\Api\PlayerType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;


class PlayerController extends BaseController
{
    /**
     * Gets a collection of the given Players.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Player",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_players_show", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View()
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function getPlayersAction(Request $request)
    {
        $playerRepo = $this->getPlayerRepository();
        $qb = $playerRepo->findAll();
        if (!$qb) {
            throw $this->createNotFoundException('Data not found.');
        }
        $paginatedCollection = $this->get('pagination_factory')
            ->createCollection($qb, $request, 'api_game_players_show');

        return $paginatedCollection;
    }

    /**
     * Gets a single Player.
     *
     * @ApiDoc(
     *     output="Club\GameBundle\Entity\Player",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when not found"
     *     }
     * )
     * @Rest\Get(name="api_game_player_show", options={ "method_prefix" = false })
     * @Rest\Route(requirements={"_format"="json|xml"})
     * @Rest\View(serializerGroups={"Details", "Default","list"})
     * @param $slug
     * @return
     */
    public function getPlayerAction($slug)
    {
        $player = $this->getPlayerRepository()
            ->findOneBySlug($slug);

        if (!$player) {
            throw $this->createNotFoundException('Data not found.');
        }
        return $player;
    }

    /**
     * @Rest\Route(requirements={"_format"="json|xml"}, path="/player")
     * @Rest\View(serializerGroups={"Details", "Default","list"}, statusCode=201)
     * @param Request $request
     * @return mixed
     */
    public function postPlayerAction(Request $request, Player $player)
    {

        $form = $this->createForm(PlayerType::class, $player);

        //$this->processForm($request, $form);
        
        $controller = $this->get('club_blog_base_controller');
        $controller->processForm($request, $form);

        //$form->handleRequest($request);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($player);
        $em->flush();

        $location = $this->generateUrl('api_game_player_show', [
            'slug' => $player->getSlug()
        ]);

        return $this->view($player, 201, ['Location' => $location]);

    }

}

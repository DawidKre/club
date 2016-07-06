<?php

namespace Club\GameBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ClubController extends BaseController
{
    /**
     *
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(
     *     "/info/{slug}/",
     *     name="game_club_info"
     * )
     */
    public function infoAction($slug)
    {
        $teamRepo = $this->getTeamRepository();
        $team = $teamRepo->findOneBySlug($slug);
        
        if (null === $team) {
            throw $this->createTeamNotFoundHttpException($slug);
        }
        return $this->render('ClubGameBundle:Club:info.html.twig', array(
            'team' => $team,
            'siteTitle' => $team->getName()
        ));
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/stadion", name="game_club_stadion")
     */
    public function stadionAction()
    {
        return $this->render('ClubGameBundle:Club:stadion.html.twig', array());
    }

    /**
     *
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/kadra/{slug}", name="game_club_cadre")
     */
    public function cadreAction($slug)
    {
        $playerRepo = $this->getPlayerRepository();
        $teamRepo = $this->getTeamRepository();
        $team = $teamRepo->findOneBySlug($slug);

        $season = $this->getSeasonRepository()->findAll();
        $cadre = $playerRepo->getCadre($slug);

        if (null === $cadre) {
            throw $this->createCadreNotFoundHttpException($slug);
        }

        return $this->render('ClubGameBundle:Club:cadre.html.twig', array(
            'cadre' => $cadre,
            'team' => $team->getName(),
            'slug' => $team->getSlug(),
            'season' => $season
        ));
    }

    /**
     *
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(
     *     "/player/{slug}/",
     *     name="game_player_info"
     * )
     */
    public function playerInfoAction($slug)
    {
        $playerRepo = $this->getPlayerRepository();
        $player = $playerRepo->findOneBySlug($slug);
        
        if (null === $player) {
            throw $this->createPlayerNotFoundHttpException('Drużyna nie została znaleziona');
        }
        //var_dump($player); die;
        return $this->render('ClubGameBundle:Club:playerInfo.html.twig', array(
            'player' => $player,
            'siteTitle' => $player->getName()
        ));
    }

}

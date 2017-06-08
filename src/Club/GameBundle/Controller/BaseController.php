<?php

namespace Club\GameBundle\Controller;

use Club\BlogBundle\Api\ApiProblem;
use Club\BlogBundle\Api\ApiProblemException;
use Club\GameBundle\Entity\Assists;
use Club\GameBundle\Entity\Matches;
use Club\GameBundle\Entity\Player;
use Club\GameBundle\Entity\PlayerStats;
use Club\GameBundle\Entity\Position;
use Club\GameBundle\Entity\Round;
use Club\GameBundle\Entity\Scores;
use Club\GameBundle\Entity\Season;
use Club\GameBundle\Entity\Team;
use Club\GameBundle\Entity\TeamStats;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\FOSRestBundle;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class BaseController extends FOSRestController
{

    /**
     * @param $slug
     * @return NotFoundHttpException
     */
    protected function createTeamNotFoundHttpException($slug)
    {
        return new NotFoundHttpException(sprintf(
            'Team with slug "%s" was not found',
            $slug
        ));
    }

    /**
     * @param $slug
     * @return NotFoundHttpException
     */
    protected function createCadreNotFoundHttpException($slug)
    {
        return new NotFoundHttpException(sprintf(
            'Cadre with slug "%s" was not found',
            $slug
        ));
    }

    /**
     * @param $slug
     * @return NotFoundHttpException
     */
    protected function createPlayerNotFoundHttpException($slug)
    {
        return new NotFoundHttpException(sprintf(
            'Player with slug "%s" was not found',
            $slug
        ));
    }

    /**
     * @return NotFoundHttpException
     */
    protected function createRoundsNotFoundHttpException()
    {
        return new NotFoundHttpException(sprintf(
            'Rounds was not found'
        ));
    }

    /**
     * @return NotFoundHttpException
     */
    protected function createPlayerStatsNotFoundHttpException()
    {
        return new NotFoundHttpException(sprintf(
            'Player stats was not found'
        ));
    }

    /**
     * @return \Club\GameBundle\Repository\AssistsRepository
     */
    public function getAssistsRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Assists::class);
    }

    /**
     * @return \Club\GameBundle\Repository\MatchesRepository
     */
    public function getMatchesRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Matches::class);
    }

    /**
     * @return \Club\GameBundle\Repository\PlayerRepository
     */
    public function getPlayerRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Player::class);
    }

    /**
     * @return \Club\GameBundle\Repository\PlayerStatsRepository
     */
    public function getPlayerStatsRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(PlayerStats::class);
    }

    /**
     * @return \Club\GameBundle\Repository\PositionRepository
     */
    public function getPositionRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Position::class);
    }

    /**
     * @return \Club\GameBundle\Repository\RoundRepository
     */
    public function getRoundRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Round::class);
    }

    /**
     * @return \Club\GameBundle\Repository\ScoresRepository
     */
    public function getScoresRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Scores::class);
    }

    /**
     * @return \Club\GameBundle\Repository\SeasonRepository
     */
    public function getSeasonRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Season::class);
    }

    /**
     * @return \Club\GameBundle\Repository\TeamRepository
     */
    public function getTeamRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(Team::class);
    }

    /**
     * @return \Club\GameBundle\Repository\TeamStatsRepository
     */
    public function getTeamStatsRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository(TeamStats::class);
    }

    /**
     * @param $data
     * @return mixed|string
     */
    public function serialize($data)
    {
        $context = new SerializationContext();

        $context->setSerializeNull(true);
        $context->enableMaxDepthChecks();

        return $this->get('jms_serializer')
            ->serialize($data, 'json', $context);
    }

    /**
     * @param $data
     * @param int $statusCode
     * @return Response
     */
    public function createApiResponse($data, $statusCode = 200)
    {
        $json = $this->serialize($data);
        return new Response($json, $statusCode, [
            'Content-Type' => 'application/hal+json'
        ]);
    }

    /**
     * @param FormInterface $form
     */
    public function throwApiProblemValidationException(FormInterface $form)
    {
        $errors = $this->getErrorsFromForm($form);
        $apiProblem = new ApiProblem(400,
            ApiProblem::TYPE_VALIDATION_ERROR
        );
        $apiProblem->set('errors', $errors);

        throw new ApiProblemException($apiProblem);

    }

    /**
     * @param FormInterface $form
     * @return array
     */
    public function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     */
    public function processForm(Request $request, FormInterface $form)
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        if (null === $data) {
            $apiProblem = new ApiProblem(
                400,
                ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT
            );
            throw new ApiProblemException($apiProblem);
        }

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    /**
     * @param Request $request
     * @return \Club\BlogBundle\Pagination\PaginatedCollection
     */
    public function apiBaseGET(Request $request, $repository, $route)
    {
        $qb = $repository->findAll();
        if (!$qb) {
            throw $this->createNotFoundException('Data not found.');
        }
        $paginatedCollection = $this->get('pagination_factory')
            ->createCollection($qb, $request, $route);

        return $paginatedCollection;
    }

}

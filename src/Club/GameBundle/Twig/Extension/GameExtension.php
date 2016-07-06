<?php

namespace Club\GameBundle\Twig\Extension;


use Club\GameBundle\Entity\Matches;
use Club\GameBundle\Entity\Round;
use Club\GameBundle\Entity\TeamStats;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig_Environment;

class GameExtension extends \Twig_Extension implements \Twig_Extension_InitRuntimeInterface
{
    /**
     * @var Registry
     */
    private $doctrine;
    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * BlogExtension constructor.
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'club_game_extension';
    }


    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('print_table', array($this, 'table'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('print_last_next_round', array($this, 'lastNextRound'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('print_news_bar', array($this, 'newsBar'), array('is_safe' => array('html'))),
        );
    }

    public function initRuntime(Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function table()
    {
        $TeamStatsRepo = $this->doctrine->getRepository(TeamStats::class);
        $table = $TeamStatsRepo->getTable();

        return $this->environment->render('@ClubGame/Template/tableWidget.html.twig', array(
            'table' => $table,
        ));
    }

    public function lastNextRound()
    {
        $matchesRepo = $this->doctrine->getRepository(Matches::class);
        $lastRound = $matchesRepo->getLastRoundMatches();
        $nextRound = $matchesRepo->getNextRoundMatches();

        return $this->environment->render('@ClubGame/Template/resultsWidget.html.twig', array(
            'lastRound' => $lastRound,
            'nextRound' => $nextRound,

        ));
    }

    public function newsBar()
    {
        $matchesRepo = $this->doctrine->getRepository(Matches::class);
        $lastRound = $matchesRepo->getLastRoundMatches();

        return $this->environment->render('@ClubBlog/Template/newsBar.html.twig', array(
            'lastRound' => $lastRound,
        ));
    }
}

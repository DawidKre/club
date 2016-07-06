<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 09.05.16
 * Time: 13:38
 */

namespace Club\GameBundle\EventListener;


use Club\GameBundle\Entity\Player;
use Club\GameBundle\Entity\PlayerStats;
use Club\GameBundle\Entity\Season;
use Club\GameBundle\Entity\TeamStats;
use Doctrine\ORM\Event\LifecycleEventArgs;

class SeasonEvent
{

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        $playerRepo = $entityManager->getRepository(Player::class);
        $players = $playerRepo->findAll();

        if ($entity instanceof Season) {
            foreach ($entity->getTeam() as $team) {
                $teamStats = new TeamStats();
                $teamStats->setSeason($entity);
                $teamStats->setTeam($team);
                $entityManager->persist($teamStats);
            }
            $entityManager->flush();

            foreach ($players as $player) {
                $playerStats = new PlayerStats();
                $playerStats->setPlayer($player);
                $playerStats->setSeason($entity);
                $entityManager->persist($playerStats);
            }
            $entityManager->flush();
        }

    }
}
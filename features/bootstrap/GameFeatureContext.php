<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 11.04.16
 * Time: 18:12
 */

//namespace features\BlogBundle;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Club\BlogBundle\Entity\Post;

require_once __DIR__ . '/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

class GameFeatureContext extends RawMinkContext
{

    use KernelDictionary;

    /** @var MinkContext */
    private $minkContext;
    /** @var  FeatureContext */
    private $featureContext;
    /** @var  UserFeatureContext */
    private $UserFeatureContext;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {

    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        $this->minkContext = $environment->getContext('Behat\MinkExtension\Context\MinkContext');
        $this->featureContext = $environment->getContext(FeatureContext::class);
        $this->UserFeatureContext = $environment->getContext(UserFeatureContext::class);
    }

    /**
     * @Given /^There are (\d+) players in team "([^"]*)"$/
     */
    public function thereArePlayersInTeam($count, $teamName)
    {
        $team = $this->featureContext->createTeam($teamName);
        $this->featureContext->createPlayer($count, null, $team);
    }

    /**
     * @Given /^There is a player with name "([^"]*)" and team "([^"]*)"$/
     */
    public function thereIsAPlayerWithNameAndTeam($name, $teamName)
    {
        $team = $this->featureContext->createTeam($teamName);
        $this->featureContext->createPlayer(1, $name, $team);
    }


}
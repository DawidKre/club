<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 11.04.16
 * Time: 18:12
 */

//namespace features\BlogBundle;

use Behat\Behat\Definition\Call\Given;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

require_once __DIR__ . '/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

class UserFeatureContext extends RawMinkContext
{

    use KernelDictionary;

    /** @var MinkContext */
    private $minkContext;
    /** @var  FeatureContext */
    private $featureContext;

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
    }

    /**
     * @return \Behat\Mink\Element\DocumentElement
     */
    private function getPage()
    {
        return $this->getSession()->getPage();
    }


    /**
     * @Given there is a user :username with password :password
     */
    public function thereIsAUserWithPassword($username, $password)
    {
        $this->featureContext->createUser($username, $password);
    }

    /**
     * @Then /^I should see validation error$/
     */
    public function iShouldSeeValidationError()
    {
        $page = $this->getPage()->find('css', '.alert-danger');
        assertNotNull($page, 'Alert not found');

    }

    /**
     * @Given /^I am logged in as user "([^"]*)" with password "([^"]*)"$/
     */
    public function iAmLoggedInAsUserWithPassword($user, $password)
    {
        $this->featureContext->createUser($user, $password, 'ROLE_USER');

        $this->visitPath('/login');
        $this->getPage()->fillField('Nazwa użytkownika', $user);
        $this->getPage()->fillField('Hasło', $password);
        $this->getPage()->pressButton('Zaloguj się');
    }

    /**
     * @Given /^I should be logged in$/
     */
    public function iShouldBeLoggedIn()
    {
        $pagination = $this->getPage()->hasLink('Wyloguj');
        assertNotNull($pagination, 'Link Not Found');
    }

    /**
     * @Given /^I am logged in as user "([^"]*)" with password "([^"]*)" and email "([^"]*)"$/
     */
    public function iAmLoggedInAsUserWithPasswordAndEmail($user, $password, $email)
    {
        $this->featureContext->createUser($user, $password, 'ROLE_USER', $email);

        $this->visitPath('/login');
        $this->getPage()->fillField('Nazwa użytkownika', $user);
        $this->getPage()->fillField('Hasło', $password);
        $this->getPage()->pressButton('Zaloguj się');
    }

    /**
     * @Then /^I fill in second "([^"]*)" with "([^"]*)"$/
     */
    public function iFillInSecondWith($field, $value)
    {
        $page = $this->getSession()->getPage()->find('css', '#change_password_current');

        $page->fillField($field, $value);

    }


    /**
     * @Given /^there is article with title "([^"]*)"$/
     */
    public function thereIsArticleWithTitle($title)
    {
        $this->featureContext->createArticle(1, null, null, null, null, $title);
    }

    /**
     * @Then /^wait "([^"]*)" second$/
     */
    public function waitSecond($time)
    {
        sleep($time);
    }

    /**
     * @Then /^I click "([^"]*)" button$/
     */
    public function iClickButton($value)
    {
        throw new PendingException();
    }


}
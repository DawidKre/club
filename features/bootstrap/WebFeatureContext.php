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

class WebFeatureContext extends RawMinkContext
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

//    /**
//     * Saving a screenshot
//     *
//     * @When I save a screenshot to :filename
//     */
//    public function iSaveAScreenshotIn($filename)
//    {
//        sleep(1);
//        $this->saveScreenshot($filename, __DIR__ . '/../..');
//    }

//    /**
//     * Pauses the scenario until the user presses a key. Useful when debugging a scenario.
//     *
//     * @Then (I )put a breakpoint
//     */
//    public function iPutABreakpoint()
//    {
//        fwrite(STDOUT, "\033[s    \033[93m[Breakpoint] Press \033[1;93m[RETURN]\033[0;93m to continue...\033[0m");
//        while (fgets(STDIN, 1024) == '') {
//        }
//        fwrite(STDOUT, "\033[u");
//        return;
//    }
    /**
     * @Given there is/are :count article(s)
     */
    public function thereAreArticles($count)
    {
        $this->featureContext->createArticle($count);

    }

    /**
     * @Then I click :linkName
     * @param $linkName
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iClick($linkName)
    {
        $this->getPage()->clickLink($linkName);
    }

    /**
     * @return \Behat\Mink\Element\DocumentElement
     */
    private function getPage()
    {
        return $this->getSession()->getPage();
    }

    /**
     * @Then I should see :page on pagination
     * @param $page
     */
    public function iShouldSeeOnPagination($page)
    {
        $pagination = $this->getPage()->find('css', '.kopa-pagination');
        assertNotNull($pagination, 'Cannot find a pagination');
        assertContains($page, $pagination->getText());
    }

    /**
     * @Then I should not see :page on pagination
     * @param $page
     */
    public function iShouldNotSeeOnPagination($page)
    {
        $pagination = $this->getPage()->find('css', '.kopa-pagination');
        assertNotNull($pagination, 'Cannot find a pagination');
        assertNotContains($page, $pagination->getText());


    }

    /**
     * @Given there are :pages pages
     * @param $pages
     */
    public function thereArePages($pages)
    {
        $pageRange = $this->getContainer()->getParameter('post_per_page');
        $totalArticles = $pages * $pageRange - 1;

        $this->featureContext->createArticle($totalArticles);
        
    }

    /**
     * @Then I should see page range articles
     */
    public function iShouldSeePageRangeArticles()
    {
        $pageRange = $this->getContainer()->getParameter('post_per_page');
        $this->iShouldSeeArticles($pageRange);
    }

    /**
     * @Then I should see :count articles
     * @param $count
     */
    public function iShouldSeeArticles($count)
    {
        $table = $this->getPage()->find('css', '.kopa-main-col');
        assertNotNull($table, 'Cannot find a article!');
        assertCount(intval($count), $table->findAll('css', '.articles123'));
    }

    /**
     * @Then I click :page on pagination
     */
    public function iClickOnPagination($page)
    {
        $pagination = $this->getPage()->find('css', '.kopa-pagination');
        assertNotNull($pagination->findLink($page, 'Link Not Found'));
        $pagination->clickLink($page);

    }

    /**
     * @Then I should see :page current
     */
    public function iShouldSeeCurrent($page)
    {
        $pagination = $this->getPage()->find('css', '.kopa-pagination');
        assertNotNull($current = $pagination->find('css', '.current'), 'Link not current');
        assertContains($page, $current->getText());
    }

    /**
     * @Then I should see :page current nav
     */
    public function iShouldSeeCurrentNav($page)
    {
        $pagination = $this->getPage()->find('css', '.kopa-main-nav');
        assertNotNull($current = $pagination->find('css', '.current-menu-item'), 'Link not current');
        assertContains($page, $current->getText());
    }

    /**
     * @Then I should see :arrow arrow on pagination
     * @param $arrow
     */
    public function iShouldSeeArrowOnPagination($arrow)
    {
        if ($arrow == 'left') {
            $pagination = $this->getPage()->find('css', '.kopa-pagination');
            assertNotNull($current = $pagination->find('css', '.prev'), 'Arrow not found');
        } elseif ($arrow == 'right') {
            $pagination = $this->getPage()->find('css', '.kopa-pagination');
            assertNotNull($current = $pagination->find('css', '.next'), 'Arrow not found');
        } else {
            throw new PendingException();
        }
    }

    /**
     * @Then I should not see :arrow arrow on pagination
     * @param $arrow
     */
    public function iShouldNotSeeArrowOnPagination($arrow)
    {
        if ($arrow == 'left') {
            $pagination = $this->getPage()->find('css', '.kopa-pagination');
            assertNull($current = $pagination->find('css', '.prev'), 'Arrow not found');
        } elseif ($arrow == 'right') {
            $pagination = $this->getPage()->find('css', '.kopa-pagination');
            assertNull($current = $pagination->find('css', '.next'), 'Arrow not found');
        } else {
            throw new PendingException();
        }
    }

    /**
     * @Then I click :arrow arrow
     * @param $arrow
     */
    public function iClickArrow($arrow)
    {
        if ($arrow == 'right') {
            $link = '.next';
        } else {
            $link = '.prev';
        }
        $pagination = $this->getPage()->find('css', '.kopa-pagination')->find('css', $link);
        assertNotNull($pagination, 'Link Not Found');
        $pagination->click();
    }

    /**
     * @Given /^I should see page range minus one articles$/
     */
    public function iShouldSeePageRangeMinusOneArticles()
    {
        $postPerPage = intval($this->getContainer()->getParameter('post_per_page'));
        $pageRange = $postPerPage - 1;
        $this->iShouldSeeArticles($pageRange);
    }


    /**
     * @Given /^There are (\d+) articles with category "([^"]*)"$/
     */
    public function thereAreArticlesWithCategory($count, $category)
    {
        $this->featureContext->createArticle($count, null, null, null, $category);
    }
    /**
     * @Given /^There are (\d+) categories contains name "([^"]*)"$/
     */
    public function thereAreCategoriesContainsName($count, $name)
    {
        for ($i = 0; $i < $count; $i++){
            $this->featureContext->createArticle(1, null, null, null, $name.$i);
        }
    }


    /**
     * @Then /^I follow "([^"]*)" category link$/
     */
    public function iFollowCategoryLink($category)
    {
        $pagination = $this->getPage()->find('css', '.category');
        assertNotNull($pagination->findLink($category, 'Link Not Found'));
        $pagination->clickLink($category);

    }

    /**
     * @Given /^There are (\d+) articles with title "([^"]*)"$/
     */
    public function thereAreArticlesWithTitle($count, $title)
    {
        $this->featureContext->createArticle($count, null, null, null, null, $title);
    }


    /**
     * @When /^I fill in the search box with "([^"]*)"$/
     */
    public function iFillInTheSearchBoxWith($term)
    {
        $searchBox = $this->getPage()
            ->find('css', '#search');
        assertNotNull($searchBox, 'The search box was not found');

        $searchBox->setValue($term);
    }

    /**
     * @Given /^I press the search button$/
     */
    public function iPressTheSearchButton()
    {
        $button = $this->getPage()
            ->find('css', '#button-search');
        assertNotNull($button, 'The search button could not be found');
        $button->press();
    }


    /**
     * @Given /^There are article with (\d+) comments with body "([^"]*)"$/
     */
    public function thereAreArticleWithCommentsWithBody($count, $comment)
    {
        $featureContext = $this->featureContext;

        $featureContext->createArticle(1, null, null, null, null, 'Article');
        $this->UserFeatureContext->iAmLoggedInAsUserWithPassword('user', 'pass');
        $this->getPage()->clickLink('Article');

        for ($i = 0; $i < $count; $i++) {
            $this->getPage()->fillField('Komentarz', rand(1, 100) . ' ' . $comment);
            $this->getPage()->pressButton('Prześlij');
        }
        
    }

    /**
     * @Given /^There are article with title "([^"]*)" and with (\d+) comments with body "([^"]*)"$/
     */
    public function thereAreArticleWithTitleAndWithCommentsWithBody($title, $count, $comment)
    {
        $featureContext = $this->featureContext;

        $featureContext->createArticle(1, null, null, null, null, $title);
        $this->UserFeatureContext->iAmLoggedInAsUserWithPassword('user', 'pass');
        $this->getPage()->clickLink('Article');

        for ($i = 0; $i < $count; $i++) {
            $this->getPage()->fillField('Komentarz', rand(1, 100) . ' ' . $comment);
            $this->getPage()->pressButton('Prześlij');
        }

    }

    /**
     * @Given /^I should see (\d+) posts headlines in side bar$/
     */
    public function iShouldSeePostsHeadlinesInSideBar($count)
    {
        $headlines = $this->getPage()->find('css', '#headlines');
        assertNotNull($headlines, 'Cannot find a headlines');
        assertCount(intval($count), $headlines->findAll('css', '#headline'));

    }

    /**
     * @Given /^I should not see posts headlines in side bar$/
     */
    public function iShouldNotSeePostsHeadlinesInSideBar()
    {
        $widget = $this->getPage()->find('css', '#headlines');
        assertNull($widget);

    }

    /**
     * @Given /^I should see (\d+) latest comments$/
     */
    public function iShouldSeeLatestComments($count)
    {
        $news = $this->getPage()->find('css', '.tab-content');
        assertNotNull($news, 'Cannot find a news');
        assertCount(intval($count), $news->findAll('css', '#new'));
    }


    /**
     * @Given /^There are (\d+) articles with author "([^"]*)"$/
     */
    public function thereAreArticlesWithAuthor($count, $author)
    {

        $this->featureContext->createArticle($count, $author);
        
    }


}
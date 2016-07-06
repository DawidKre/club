<?php

//namespace features\BlogBundle;
use Behat\Behat\Definition\Call\Given;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

use GuzzleHttp\Psr7\Response;
use Symfony\Component\Console\Output\ConsoleOutput;


require_once __DIR__ . '/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

class ApiFeatureContext extends RawMinkContext
{

    use KernelDictionary;

    /** @var MinkContext */
    private $minkContext;
    /** @var  FeatureContext */
    private $featureContext;

    /**
     * 
     *
     * @var Response
     */
    protected $response;
    /**
     * @var ConsoleOutput
     */
    protected $output;


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
     * @Given I clear the database
     */
    public function iClearTheDatabase()
    {
        $this->featureContext->clearData();
    }
//    /**
//     * @Given /^I have the payload:$/
//     * @param PyStringNode $requestPayload
//     */
//    public function iHaveThePayload(PyStringNode $requestPayload)
//    {
//        $this->requestPayload = $requestPayload;
//    }
//
//    /**
//     * @Given /^print last api response$/
//     */
//    public function printLastResponse()
//    {
// 
//        if ($this->response) {
//            $response = clone ($this->response);
//            $body = $response->getBody();
//            $data = json_decode($body, true);
//
//            if ($data) {
//                $response->setBody(json_encode($data, JSON_PRETTY_PRINT));
//            }
//
//            $this->printDebug((string) $response);
//        }
//      
//    }
//
    /**
     * @return Response
     */
    public function getResponse()
    {
        if ($this->response === null) {
            throw new \RuntimeException('There is no response.');
        }
        return $this->response;
    }
    
    /**
     * @return ConsoleOutput
     */
    private function getOutput()
    {
        if ($this->output === null)  {
            $this->output = new ConsoleOutput();
        }

        return $this->output;
    }
    
    
    public function printDebug($string)
    {
        $this->getOutput()->writeln($string);
    }
    
    /**
     * @Then /^the response api status code should be (?P<code>\d+)$/
     */
    public function iGetAResponse($statusCode)
    {
        $response = $this->getResponse();
        $contentType = $response->getHeader('Content-Type');

        // looks for application/json or something like application/problem+json
        if (preg_match('#application\/(.)*\+?json#', $contentType)) {
            $bodyOutput = $response->getBody();
        } else {
            $bodyOutput = 'Output is "'.$contentType.'", which is not JSON and is therefore scary. Run the request manually.';
        }
        assertSame((int) $statusCode, (int) $this->getResponse()->getStatusCode(), $bodyOutput);
    }
}
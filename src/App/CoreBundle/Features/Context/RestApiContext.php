<?php

namespace App\CoreBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Post\PostFile;
use PHPUnit_Framework_Assert as Assertions;
use Sanpi\Behatch\Json\JsonInspector;
use Sanpi\Behatch\Json\JsonSchema;
use Namshi\Cuzzle\Formatter\CurlFormatter;
use Symfony\Component\PropertyAccess\PropertyAccess;


/**
 * Class RestApiContext
 * @package AppBundle\Features\Context
 */
class RestApiContext implements Context
{
    protected $debug = true;
    private $twig;

    /**
     * @var string
     */
    private $authorization;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var array
     */
    private $placeHolders = array();
    /**
     * @var string
     */
    private $dummyDataPath;

    /**
     * RestApiContext constructor.
     */
    public function __construct($settings)
    {
        $settings = array_merge(
            [
                'debug' => false,
                'base_url' => '',
            ],
            $settings
        );
        $this->client = new Client(
            [
                'base_uri' => $settings['base_url'],
                'timeout' => 2.0,
            ]
        );
        $this->debug = $settings['debug'];
    }

    /**
     * Adds JWT Token to Authentication header for next request
     *
     * @param string $username
     * @param string $password
     *
     * @Given I get a jtw token with :username and :password
     */
    public function iGetAJwtToken($username, $password)
    {
        $params = [
            '_username' => $username,
            '_password' => $password,
        ];
        $body = \GuzzleHttp\Psr7\stream_for(\GuzzleHttp\json_encode($params));
        $options['Content-Type'] = 'application/json';
        $this->request = new Request('POST', '/api/login_check', $options, $body);
        $this->sendRequest();
        $response = $this->response;
        \PHPUnit_Framework_Assert::assertEquals(200, $response->getStatusCode());
        $responseBody = json_decode($response->getBody(), true);
        $this->setHeader('Authorization', 'Bearer '.$responseBody['token']);
    }

    /**
     * @Given when consuming the endpoint I use the :header of :value
     */
    public function whenConsumingTheEndpointIUseTheOf($header, $value)
    {
        $this->client->setDefaultOption($header, $value);
    }

    /**
     * @When I have forgotten to set the :header
     */
    public function iHaveForgottenToSetThe($header)
    {
        $this->client->setDefaultOption($header, null);
    }

    /**
     * Sets a HTTP Header.
     *
     * @param string $name header name
     * @param string $value header value
     *
     * @Given /^I set header "([^"]*)" with value "([^"]*)"$/
     */
    public function iSetHeaderWithValue($name, $value)
    {
        $this->addHeader($name, $value);
    }

    /**
     * Sends HTTP request to specific relative URL.
     *
     * @param string $method request method
     * @param string $url relative url
     *
     * @When /^(?:I )?send a "([A-Z]+)" request to "([^"]+)"$/
     */
    public function iSendARequest($method, $url)
    {
        $url = $this->prepareUrl($url);
        $this->request = new Request($method, $url, $this->headers);

        $this->sendRequest();
    }

    /**
     * Sends HTTP request to specific URL with field values from Table.
     *
     * @param string $method request method
     * @param string $url relative url
     * @param TableNode $post table of post values
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with values:$/
     */
    public function iSendARequestWithValues($method, $url, TableNode $post)
    {
        $url = $this->prepareUrl($url);
        $fields = array();

        foreach ($post->getRowsHash() as $key => $val) {
            $fields[$key] = $this->replacePlaceHolder($val);
        }

        $bodyOption = array(
            'body' => json_encode($fields),
        );
        $this->request = new Request($method, $url, $bodyOption);
        if (!empty($this->headers)) {
            $this->request->addHeaders($this->headers);
        }

        $this->sendRequest();
    }

    /**
     * Sends HTTP request to specific URL with raw body from PyString.
     *
     * @param string $method request method
     * @param string $url relative url
     * @param PyStringNode $string request body
     *
     * @When /^(?:I )?send a "([A-Z]+)" request to "([^"]+)" with body:$/
     */
    public function iSendARequestWithBody($method, $url, PyStringNode $string)
    {
        $url = $this->prepareUrl($url);
        $string = $this->replacePlaceHolder(trim($string));

        $this->request = new Request(
            $method,
            $url,
            array(
                'headers' => $this->getHeaders(),
                'body' => $string,
            )
        );

        $this->sendRequest();
    }

    /**
     * Sends HTTP request to specific URL with form data from PyString.
     *
     * @param string $method request method
     * @param string $url relative url
     * @param PyStringNode $body request body
     *
     * @When /^(?:I )?send a "([A-Z]+)" request to "([^"]+)" with form data as querystring:$/
     */
    public function iSendARequestWithFormDataQuerystring($method, $url, PyStringNode $body)
    {
        $url = $this->prepareUrl($url);
        $this->request = new Request($method, $url, [], $body->getRaw());
        $this->sendRequest();
    }

    /**
     * Sends HTTP request to specific URL with form data from PyString.
     *
     * @param string $method request method
     * @param string $url relative url
     * @param PyStringNode $body request body
     *
     * @When /^(?:I )?send a "([A-Z]+)" request to "([^"]+)" with form data as table:$/
     */
    public function iSendARequestWithFormDataTableNode($method, $url, TableNode $body)
    {
        $url = $this->prepareUrl($url);
        $params = $this->solveDots($body->getRowsHash());
        $this->request = new Request($method, $url, [], http_build_query($params, null, '&'));
        $this->sendRequest();
    }

    /**
     * Sends HTTP request to specific URL with json from PyString.
     *
     * @param string $method request method
     * @param string $url relative url
     * @param PyStringNode $body request body
     *
     * @When /^(?:I )?send a "([A-Z]+)" request to "([^"]+)" with json as raw:$/
     */
    public function iSendARequestWithJsonQuerystring($method, $url, PyStringNode $body)
    {
        $url = $this->prepareUrl($url);
        $body = \GuzzleHttp\Psr7\stream_for($body->getRaw());
        $options['Content-Type'] = 'application/json';

        $this->request = new Request($method, $url, $options, $body);
        $this->sendRequest();
    }

    /**
     * Sends HTTP request to specific URL with form data from PyString.
     *
     * @param string $method request method
     * @param string $url relative url
     * @param PyStringNode $body request body
     *
     * @When /^(?:I )?send a "([A-Z]+)" request to "([^"]+)" with json as table:$/
     */
    public function iSendARequestWithJsonTableNode($method, $url, TableNode $body)
    {
        $url = $this->prepareUrl($url);
        $body = \GuzzleHttp\Psr7\stream_for(\GuzzleHttp\json_encode($this->solveDots($body->getRowsHash())));
        $options['Content-Type'] = 'application/json';
        $options = array_merge($this->headers, $options);
        $this->request = new Request($method, $url, $options, $body);
        $this->sendRequest();
    }

    private function solveDots($params)
    {
        $ret = [];
        $pa = PropertyAccess::createPropertyAccessor();
        foreach ($params as $k => $v) {
            $pa->setValue($ret, '['.str_replace('.', '][', $k).']', $v);
        }

        return $ret;
    }

    /**
     * @When /^(?:I )?send a multipart "([A-Z]+)" request to "([^"]+)" with form data:$/
     * @todo
     * @see http://docs.guzzlephp.org/en/latest/quickstart.html
     */
    public function iSendAMultipartRequestToWithFormData($method, $url, TableNode $post)
    {
        throw new PendingException;
        $url = $this->prepareUrl($url);
        $this->request = new Request($method, $url);
        $data = $post->getColumnsHash()[0];
        $this->sendRequest();
    }

    /**
     * Checks that response has specific status code.
     *
     * @param string $code status code
     *
     * @Then the response status code should be :arg1
     */
    public function theResponseCodeShouldBe($code)
    {
        $expected = intval($code);
        $actual = intval($this->response->getStatusCode());
        Assertions::assertSame($expected, $actual);
    }

    /**
     * Checks that response body contains specific text.
     *
     * @param string $text
     *
     * @Then /^(?:the )?response should contain "([^"]*)"$/
     */
    public function theResponseShouldContain($text)
    {
        $expectedRegexp = '/'.preg_quote($text).'/i';
        $actual = (string)$this->response->getBody();
        Assertions::assertRegExp($expectedRegexp, $actual);
    }

    /**
     * Checks that response body doesn't contains specific text.
     *
     * @param string $text
     *
     * @Then /^(?:the )?response should not contain "([^"]*)"$/
     */
    public function theResponseShouldNotContain($text)
    {
        $expectedRegexp = '/'.preg_quote($text).'/';
        $actual = (string)$this->response->getBody();
        Assertions::assertNotRegExp($expectedRegexp, $actual);
    }

    /**
     * Checks that response body contains JSON from PyString.
     *
     * Do not check that the response body /only/ contains the JSON from PyString,
     *
     * @param PyStringNode $jsonString
     *
     * @throws \RuntimeException
     *
     * @Then /^(?:the )?response should contain json:$/
     */
    public function theResponseShouldContainJson(PyStringNode $jsonString)
    {
        try {
            $expected = json_decode($this->replacePlaceHolder($jsonString->getRaw()), true);
            $original = (string)$this->response->getBody();
            Assertions::assertNotEmpty($original);
            Assertions::assertJson($original);

            $actual = json_decode($original, true);
            if (null === $expected) {
                throw new \RuntimeException(
                    "Can not convert expected to json:\n".$this->replacePlaceHolder($jsonString->getRaw())
                );
            }
            Assertions::assertGreaterThanOrEqual(count($expected), count($actual));
            foreach ($expected as $key => $needle) {
                Assertions::assertArrayHasKey($key, $actual);
                if (is_string($expected[$key])) {
                    Assertions::assertRegExp($this->patternize($expected[$key]), (string)$actual[$key]);
                } else {
                    Assertions::assertEquals($expected[$key], $actual[$key]);
                }
            }
        } catch (\RuntimeException $e) {
            if ($this->debug) {
                echo "original: ".(isset($actual) ? json_encode($actual, JSON_PRETTY_PRINT) : $original);
            }
            throw $e;
        }
    }

    private function patternize($pattern)
    {
        return sprintf("|%s|", str_replace('\\*', '.*', preg_quote($pattern, '/')));
    }

    /**
     * Prints last response body.
     *
     * @Then print response
     */
    public function printResponse()
    {
        $request = $this->request;
        $response = $this->response;

        echo sprintf(
            "%s %s => %d:\n%s",
            $request->getMethod(),
            $request->getUrl(),
            $response->getStatusCode(),
            $response->getBody()
        );
    }

    /**
     * @Then the response header :header should be equal to :value
     */
    public function theResponseHeaderShouldBeEqualTo($header, $value)
    {
        $header = $this->response->getHeaders()[$header];
        Assertions::assertContains($value, $header);
    }

    /**
     * Prepare URL by replacing placeholders and trimming slashes.
     *
     * @param string $url
     *
     * @return string
     */
    private function prepareUrl($url)
    {
        return ltrim($this->replacePlaceHolder($this->render($url)), '/');
    }

    /**
     * Sets place holder for replacement.
     *
     * you can specify placeholders, which will
     * be replaced in URL, request or response body.
     *
     * @param string $key token name
     * @param string $value replace value
     */
    public function setPlaceHolder($key, $value)
    {
        $this->placeHolders[$key] = $value;
    }

    /**
     * @Then the I follow the link in the Location response header
     */
    public function theIFollowTheLinkInTheLocationResponseHeader()
    {
        $location = $this->response->getHeader('Location');

        $this->iSendARequest(Request::GET, $location);
    }

    /**
     * @Then the JSON should be valid according to this schema:
     */
    public function theJsonShouldBeValidAccordingToThisSchema(PyStringNode $schema)
    {
        $inspector = new JsonInspector('javascript');

        $json = new \Sanpi\Behatch\Json\Json($this->response->getBody());

        $inspector->validate(
            $json,
            new JsonSchema($schema)
        );
    }

    /**
     * Checks, that given JSON node is equal to given value
     *
     * @Then the JSON node :node should be equal to :text
     */
    public function theJsonNodeShouldBeEqualTo($node, $text)
    {
        $json = new \Sanpi\Behatch\Json\Json(json_encode($this->response->getBody()));

        $inspector = new JsonInspector('javascript');

        $actual = $inspector->evaluate($json, $node);

        if ($actual != $text) {
            throw new \Exception(
                sprintf("The node value is '%s'", json_encode($actual))
            );
        }
    }

    /**
     * Replaces placeholders in provided text.
     *
     * @param string $string
     *
     * @return string
     */
    protected function replacePlaceHolder($string)
    {
        foreach ($this->placeHolders as $key => $val) {
            $string = str_replace($key, $val, $string);
        }

        return $string;
    }

    /**
     * Returns headers, that will be used to send requests.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Adds header
     *
     * @param string $name
     * @param string $value
     */
    protected function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * Adds header
     *
     * @param string $name
     * @param string $value
     */
    protected function addHeader($name, $value)
    {
        if (isset($this->headers[$name])) {
            if (!is_array($this->headers[$name])) {
                $this->headers[$name] = array($this->headers[$name]);
            }

            $this->headers[$name][] = $value;
        } else {
            $this->headers[$name] = $value;
        }
    }

    /**
     * Removes a header identified by $headerName
     *
     * @param string $headerName
     */
    protected function removeHeader($headerName)
    {
        if (array_key_exists($headerName, $this->headers)) {
            unset($this->headers[$headerName]);
        }
    }

    /**
     *
     */
    private function sendRequest()
    {
        try {
            if ($this->debug) {
                echo (new CurlFormatter())->format($this->request, []);
            }
            $this->response = $this->getClient()->send($this->request);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();

            if (null === $this->response) {
                throw $e;
            }
        }
    }

    /**
     * @return Client
     */
    private function getClient()
    {
        if (null === $this->client) {
            throw new \RuntimeException('Client has not been set in WebApiContext');
        }

        return $this->client;
    }


    protected function render($url)
    {
        if (strpos($url, '{') === false) {
            return $url;
        }
        $rendered = $this->getTwig()->render(
            $url,
            array('ids' => SharedData::$ids)
        );

        return $rendered;
    }

    protected function getTwig()
    {
        if (!isset($this->twig)) {
            $this->twig = new \Twig_Environment(new \Twig_Loader_String());
        }

        return $this->twig;
    }
}
<?php
namespace KieServerClient\Rest;

use Exception;
use KieServerClient\Protocol\Response;
use RestClient\CurlRestClient;

class ClientWrapper
{
    /**
     * @var BasicAuthorization
     */
    private $basicAuthorization;

    /**
     * @var string
     */
    private $url;

    /**
     * @var Request
     */
    private $request;

    /**
     * ClientWrapper constructor.
     *
     * @param string $url The URL to connect to
     * @param Request $request Request with headers, body and method
     * @param BasicAuthorization $basicAuthorization Auth
     */
    public function __construct($url, Request $request, BasicAuthorization $basicAuthorization)
    {
        $this->url = $url;
        $this->request = $request;
        $this->basicAuthorization = $basicAuthorization;
    }

    /**
     * Execute REST request and return response
     *
     * @throws Exception
     * @return Response
     */
    public function executeRequest()
    {
        $restClient = new CurlRestClient($this->getUrl(), $this->request->getHeaders());
        switch ($this->request->getMethod()) {
            case Request::METHOD_GET:
                $response = $restClient->get($this->request->getPath(), array($this->request->getBody()));
                break;
            case Request::METHOD_POST:
                $response = $restClient->post($this->request->getPath(), array($this->request->getBody()));
                break;
            default:
                throw new Exception("Method {$this->request->getMethod()} not implemented yet");
        }

        return $this->handleResponse($response);
    }

    /**
     * @return BasicAuthorization
     */
    public function getBasicAuthorization()
    {
        return $this->basicAuthorization;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * handle response from curl request
     *
     * @param string|array $response
     * @return array|KieExecutionResponse|string
     */
    private function handleResponse($response)
    {
        $response = new KieExecutionResponse($response, $this->request->getHeaders());

        return $response;
    }
}

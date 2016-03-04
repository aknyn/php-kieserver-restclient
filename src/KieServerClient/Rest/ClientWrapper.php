<?php
namespace KieServerClient\Rest;

use Exception;
use KieServerClient\Protocol\KieServicesException;
use KieServerClient\Protocol\KieServicesResponse;
use KieServerClient\Protocol\Response;
use KieServerClient\Thrift\Deserializer;
use RestClient\CurlRestClient;
use TException;

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
     * @return KieServicesResponse
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

        $response = new KieExecutionResponse($response, $this->request->getHeaders());
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
     * Handle rest response
     *
     * @param KieExecutionResponse $restResponse
     * @return KieServicesResponse
     * @throws KieServicesException
     * @throws TException
     */
    private function handleResponse(KieExecutionResponse $restResponse)
    {
        $response = new KieServicesResponse();
        $deserializer = Deserializer::getInstance();
        $deserializer->getDeserializedFromBuffer($response, $restResponse->getBody());

        if ($response->response->kieServicesException instanceof KieServicesException) {
            throw $response->response->kieServicesException;
        }

        return $response;
    }
}

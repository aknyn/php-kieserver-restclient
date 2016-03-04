<?php
namespace Examples;

require_once dirname(__FILE__) . '/../bootstrap.php';

use KieServerClient\Protocol\KieServicesClient;
use KieServerClient\Protocol\KieServicesRequest;
use KieServerClient\Rest\BasicAuthorization;
use KieServerClient\Rest\ClientWrapper;
use KieServerClient\Rest\KieExecutionRequest;
use KieServerClient\Rest\Request;
use KieServerClient\Thrift\Serializer;

$thriftSerializer = Serializer::getInstance();

$kieServicesClient = new KieServicesClient();
$kieServicesClient->client = "ServerInfoTestClient";

$serverInfoTestRequest = new KieServicesRequest();
$serverInfoTestRequest->kieServicesClient = $kieServicesClient;

$basicAuth = new BasicAuthorization("testuser", "testpasswd");
$executionRequest = new KieExecutionRequest($basicAuth);
$executionRequest->method = Request::METHOD_GET;
$executionRequest->body = $thriftSerializer->getSerializedFromBuffer($serverInfoTestRequest);
$executionRequest->path = '/kie-server/services/rest/server/thrift/containers';

$client = new ClientWrapper('http://www.google.com', $executionRequest, $basicAuth);
$response = $client->executeRequest();

var_dump($response);
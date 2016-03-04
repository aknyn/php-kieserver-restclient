<?php
namespace KieServerClient\Rest;

/**
 * Class RestKieExecutionRequest
 *
 * @author aknyn <knyn@hmmdeutschland.de>
 * @package KieServerClient\Request
 */
class KieExecutionRequest implements Request
{
    const APPLICATION_XTHRIFT = 'APPLICATION_XTHRIFT';

    /**
     * @var string
     */
    public $body;

    /**
     * @var string
     */
    public $method;

    /**
     * @var string
     */
    public $path;

    /**
     * @var BasicAuthorization
     */
    public $basicAuthorization;

    /**
     * KieExecutionRequest constructor.
     *
     * @param BasicAuthorization $basicAuthorization
     */
    public function __construct(BasicAuthorization $basicAuthorization)
    {
        $this->basicAuthorization = $basicAuthorization;
    }

    /**
     * @inheritdoc
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return array(
            'Content-Type' => self::APPLICATION_XTHRIFT,
            'Authorization' => $this->basicAuthorization->getAuthToken()
        );
    }

    /**
     * @inheritdoc
     */
    public function getContent()
    {
        return $this->getBody();
    }
}

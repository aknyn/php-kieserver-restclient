<?php
namespace KieServerClient\Rest;

/**
 * Class KieExecutionResponse
 *
 * @author Alexander Knyn <ich@alexander-knyn.de>
 * @package KieServerClient\Rest
 */
class KieExecutionResponse implements Response
{
    /**
     * @var string
     */
    public $body;

    /**
     * @var array
     */
    public $headers = array();

    /**
     * KieExecutionResponse constructor.
     *
     * @param $body
     * @param array $headers
     */
    public function __construct($body, array $headers)
    {
        $this->body = $body;
        $this->headers = $headers;
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
        return $this->headers;
    }
}

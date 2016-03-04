<?php
namespace KieServerClient\Rest;

/**
 * Interface RestRequest
 *
 * @author Alexander Knyn <ich@alexander-knyn.de>
 * @package KieServerClient\Request
 */
interface Response
{
    /**
     * Key-value-pairs to send via header
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Request-body
     *
     * @return string
     */
    public function getBody();

}

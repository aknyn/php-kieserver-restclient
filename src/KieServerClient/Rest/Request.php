<?php
namespace KieServerClient\Rest;

/**
 * Interface RestRequest
 *
 * @author Alexander Knyn <ich@alexander-knyn.de>
 * @package KieServerClient\Request
 */
interface Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    const METHOD_PATCH = 'PATCH';
    const METHOD_HEAD = 'HEAD';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_CONNECT = 'CONNECT';
    const METHOD_TRACE = 'TRACE';

    /**
     * Path on server side
     *
     * @return string
     */
    public function getPath();

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

    /**
     * Get method to use
     *
     * @return string
     */
    public function getMethod();

    /**
     * Get content to send. This only applies to these methods:
     * <ul>
     *  <li>POST</li>
     *  <li>PATCH</li>
     *  <li>PUT</li>
     * </ul>
     *
     * @return string|null
     */
    public function getContent();

}

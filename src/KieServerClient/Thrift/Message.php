<?php
namespace KieServerClient\Thrift;

use Thrift\Protocol\TProtocol;
use Thrift\Transport\TTransport;

/**
 * Class ThriftMessage
 *
 * @author Alexander Knyn <ich@alexander-knyn.de>
 * @package KieServerClient\Thrift
 */
class Message
{
    /**
     * @var TProtocol
     */
    protected $protocol;

    /**
     * @var TTransport
     */
    protected $transport;

    /**
     * ThriftSerializer constructor.
     *
     * @param TTransport $transport
     * @param TProtocol $protocol
     */
    public function __construct(TTransport $transport, TProtocol $protocol)
    {
        $this->protocol = $protocol;
        $this->transport = $transport;
    }
}

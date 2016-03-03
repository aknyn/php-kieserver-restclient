<?php
namespace KieServerClient\Thrift;

use Thrift\Base\TBase;
use Thrift\Exception\TException;
use Thrift\Protocol\TCompactProtocol;
use Thrift\Transport\TMemoryBuffer;

/**
 * Class ThriftSerializer
 *
 * @author aknyn <knyn@hmmdeutschland.de>
 * @package hmm\zhponline\base\thrift
 */
class Serializer extends Message
{
    /**
     * generate serialized object from TMemoryBuffer
     *
     * @param TBase $object
     * @return string
     * @throws TException
     */
    public function getSerializedFromBuffer(TBase $object)
    {
        $object->write($this->protocol);
        $this->protocol->getTransport()->flush();

        if($this->transport instanceof TMemoryBuffer) {
            return $this->transport->getBuffer();
        } else {
            throw new TException("unsupported TTransport instance");
        }
    }

    /**
     * Get instance with TMemoryBuffer and TCompactProtocol
     *
     * @return Serializer
     */
    public static function getInstance()
    {
        $transport = new TMemoryBuffer();
        $protocol = new TCompactProtocol($transport);

        return new self($transport, $protocol);

    }

}

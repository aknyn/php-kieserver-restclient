<?php
namespace KieServerClient\Thrift;

use Thrift\Base\TBase;
use Thrift\Protocol\TCompactProtocol;
use Thrift\Transport\TMemoryBuffer;

/**
 * Class Deserializer
 *
 * @author Alexander Knyn <ich@alexander-knyn.de>
 * @package KieServerClient\Thrift
 */
class Deserializer extends Message
{
    /**
     * generate serialized object from TMemoryBuffer
     *
     * @param TBase $object
     * @param string $serialized
     *
     * @return object
     */
    public function getDeserializedFromBuffer(TBase $object, $serialized)
    {
        $this->transport->write($serialized);
        $object->read($this->protocol);
    }

    /**
     * Get instance with TMemoryBuffer and TCompactProtocol
     *
     * @return Deserializer
     */
    public static function getInstance()
    {
        $transport = new TMemoryBuffer();
        $protocol = new TCompactProtocol($transport);

        return new self($transport, $protocol);

    }

}

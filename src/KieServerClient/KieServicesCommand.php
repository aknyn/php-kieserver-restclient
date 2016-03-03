<?php
namespace KieServerClient;

use KieServerClient\Protocol\AgendaFilter;
use KieServerClient\Protocol\BatchExecutionCommand;
use KieServerClient\Protocol\FireAllRulesCommand;
use KieServerClient\Protocol\InsertElementsCommand;
use KieServerClient\Protocol\InsertObjectCommand;
use KieServerClient\Protocol\SetGlobalCommand;
use KieServerClient\Thrift\Serializer;
use ReflectionClass;
use Thrift\Base\TBase;
use Thrift\Exception\TException;

/**
 * Class KieCommandManagerImpl
 *
 * @author aknyn <knyn@hmmdeutschland.de>
 * @package hmm\zhponline\base\kie
 */
class KieServicesCommand
{
    /**
     * @var InsertObjectCommand[]
     */
    private $insertObjectCommands = array();

    /**
     * @var SetGlobalCommand[]
     */
    private $setGlobalCommands = array();

    /**
     * @var FireAllRulesCommand
     */
    private $fireAllRulesCommand;

    /**
     * @var InsertElementsCommand
     */
    private $insertElementsCommand;

    /**
     * KieCommandManagerImpl constructor
     */
    public function __construct()
    {
        // create default -> can be overwritten by calling this method separated
        $this->createFireAllRulesCommand();
    }

    /**
     * Create FireAllRulesCommand object
     *
     * @param int $max [default=-1]
     * @param string $outIdentifier [default=null]
     * @param AgendaFilter $agendaFilter [default=null]
     */
    public function createFireAllRulesCommand($max = -1, $outIdentifier = null, AgendaFilter $agendaFilter = null)
    {
        $fireAllRulesCommand = new FireAllRulesCommand();
        if ($max != $fireAllRulesCommand->max) {
            $fireAllRulesCommand->max = $max;
        }
        if ($outIdentifier !== null) {
            $fireAllRulesCommand->outIdentifier = $outIdentifier;
        }
        if ($agendaFilter instanceof AgendaFilter) {
            $fireAllRulesCommand->agendaFilter = $agendaFilter;
        }

        $this->fireAllRulesCommand = $fireAllRulesCommand;
    }

    /**
     * Generate InsertElementsCommand object
     *
     * @param TBase[] $elementObjects
     * @param string $classCanonicalName
     * @param bool $returnObject [default=true]
     * @param null $entryPoint [default=null]
     * @param null $outIdentifier [default=null]
     */
    public function createInsertElementsCommand(
        array $elementObjects,
        $classCanonicalName,
        $returnObject = true,
        $entryPoint = null,
        $outIdentifier = null
    ) {
        $thriftSerializer = Serializer::getInstance();
        foreach ($elementObjects AS &$elementObject) {
            $elementObject = $thriftSerializer->getSerializedFromBuffer($elementObject);
        }

        $insertElementsCommand = new InsertElementsCommand($elementObjects, $classCanonicalName);
        if ($returnObject !== null) {
            $insertElementsCommand->returnObject = $returnObject;
        }
        if ($entryPoint !== null) {
            $insertElementsCommand->entryPoiny = $entryPoint;
        }
        if ($outIdentifier !== null) {
            $insertElementsCommand->outIdentifier = $outIdentifier;
        }

        $this->insertElementsCommand = $insertElementsCommand;
    }

    /**
     * Generate BatchExecutionCommand object
     *
     * @return BatchExecutionCommand
     */
    public function getBatchExecutionCommand()
    {
        $batchExecutionCommand = new BatchExecutionCommand();
        if (count($this->setGlobalCommands) > 0) {
            $batchExecutionCommand->setGlobalCommands = $this->setGlobalCommands;
        }
        if (count($this->insertObjectCommands) > 0) {
            $batchExecutionCommand->insertObjectCommands = $this->insertObjectCommands;
        }
        if ($this->insertElementsCommand instanceof InsertElementsCommand) {
            $batchExecutionCommand->insertElementsCommands = $this->insertElementsCommand;
        }
        $batchExecutionCommand->fireAllRulesCommand = $this->fireAllRulesCommand;

        return $batchExecutionCommand;
    }

    /**
     * Generate InsertObjectCommand object
     *
     * @param TBase $object
     * @param string $classCanonicalName
     * @param bool $returnObject [default=true]
     * @param string $entryPoint [default=null]
     * @param string $outIdentifier [default=null] class shortname will be used if null given
     * @throws TException
     */
    public function addInsertObjectCommand(
        TBase $object,
        $classCanonicalName,
        $returnObject = true,
        $entryPoint = null,
        $outIdentifier = null
    ) {
        $thriftSerializer = Serializer::getInstance();
        $insertObjectCommand = new InsertObjectCommand();
        $insertObjectCommand->object = $thriftSerializer->getSerializedFromBuffer($object);
        $insertObjectCommand->classCanonicalName = $classCanonicalName;
        $insertObjectCommand->returnObject = $returnObject;

        if ($entryPoint !== null) {
            $insertObjectCommand->entryPoiny = $entryPoint;
        }

        if ($outIdentifier !== null) {
            $insertObjectCommand->outIdentifier = $outIdentifier;
        } else {
            $insertObjectCommand->outIdentifier = $this->getClassShortName($object);
        }

        $this->insertObjectCommands[] = $insertObjectCommand;
    }

    /**
     * Generate SetGlobalCommand object
     *
     * @param TBase $globalObject
     * @param $classCanonicalName
     * @param null $identifier
     * @param null $outIdentifier
     * @throws TException
     */
    public function addSetGlobalCommand(
        TBase $globalObject,
        $classCanonicalName,
        $identifier = null,
        $outIdentifier = null
    ) {
        $thriftSerializer = Serializer::getInstance();
        $setGlobalCommand = new SetGlobalCommand();
        $setGlobalCommand->object = $thriftSerializer->getSerializedFromBuffer($globalObject);
        $setGlobalCommand->classCanonicalName = $classCanonicalName;
        if ($identifier !== null) {
            $setGlobalCommand->identifier = $identifier;
        }
        if ($outIdentifier !== null) {
            $setGlobalCommand->outIdentifier = $outIdentifier;
        }

        $this->setGlobalCommands[] = $setGlobalCommand;
    }

    /**
     * Get classname without namespace
     *
     * @param TBase $object
     * @return string
     */
    protected function getClassShortName(TBase $object)
    {
        $reflectionClass = new ReflectionClass($object);

        return $reflectionClass->getShortName();
    }

}


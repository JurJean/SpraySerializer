<?php

namespace Spray\Serializer;

use Closure;

abstract class BoundClosureSerializer implements SerializerInterface, ConstructorInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var Closure
     */
    private $serializer;

    /**
     * @var Closure
     */
    private $deserializer;

    /**
     * @var object
     */
    private $constructed;

    /**
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = (string) $class;
    }

    /**
     * Check if $subject can be serialized.
     *
     * @param mixed $subject

     * @return boolean
     */
    public function accepts($subject)
    {
        if (is_object($subject)) {
            $subject = get_class($subject);
        }
        return $subject === $this->class;
    }

    /**
     * Construct a new object.
     *
     * By default a new empty object is deserialized and from then on cloned.
     *
     * @param string $subject The class name of the object to create
     * @param array  $data    The data to deserialize
     *
     * @return object
     */
    public function construct($subject, &$data = array())
    {
        if (null === $this->constructed) {
            $this->constructed = unserialize(
                sprintf(
                    'O:%d:"%s":0:{}',
                    strlen($subject),
                    $subject
                )
            );
        }
        return clone $this->constructed;
    }

    /**
     * Get a reference to the bound serialization closure.
     *
     * @return Closure
     */
    protected function serializer()
    {
        if (null === $this->serializer) {
            $self = $this;
            $this->serializer = Closure::bind($this->bindSerializer(), null, $this->class);
        }
        return $this->serializer;
    }

    /**
     * Return function that fills $data with $subject properties.
     *
     * return function() {
     *     $data['foo'] = $subject->foo;
     * };
     *
     * @param object              $subject    The object being serialized
     * @param array               $data       The data to map properties to
     * @param SerializerInterface $serializer Pass data to parent serializer
     *
     * @return void
     */
    abstract protected function bindSerializer();

    /**
     * Turn $subject (object) into $data (array).
     *
     * @param object              $subject
     * @param array               $data
     * @param SerializerInterface $serializer
     *
     * @return object
     */
    public function serialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {
        $context = $this->serializer();
        $context($subject, $data, $serializer);
        return $data;
    }

    /**
     * Get a reference to the bound deserialization closure.
     *
     * @return Closure
     */
    protected function deserializer()
    {
        if (null === $this->deserializer) {
            $self = $this;
            $this->deserializer = Closure::bind($this->bindDeserializer(), null, $this->class);
        }
        return $this->deserializer;
    }

    /**
     * Return function that maps $data to $subject properties.
     *
     * return function($subject, &$data = array(), SerializerInterface $serializer = null) {
     *     $subject->foo = $data['foo'];
     * };
     *
     * @param object              $subject    The object being deserialized
     * @param array               $data       The data to put into properties
     * @param SerializerInterface $serializer Pass data to parent serializer
     *
     * @return void
     */
    abstract protected function bindDeserializer();

    /**
     * Turn $data (array) into $subject (object).
     *
     * @param object $subject
     * @param array $data
     * @param SerializerInterface $serializer
     *
     * @return object
     */
    public function deserialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {
        $context = $this->deserializer();
        $context($subject, $data, $serializer);
        return $subject;
    }
}

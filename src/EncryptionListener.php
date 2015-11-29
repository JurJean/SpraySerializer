<?php

namespace Spray\Serializer;

use Spray\Serializer\Encryption\EncryptorLocator;
use Zend\Crypt\BlockCipher;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

class EncryptionListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @var EncryptorLocator
     */
    private $locator;

    /**
     * @var BlockCipher
     */
    private $blockCypher;

    /**
     * @param EncryptorLocator $locator
     * @param BlockCipher $blockCipher
     */
    public function __construct(EncryptorLocator $locator, BlockCipher $blockCipher)
    {
        $this->locator = $locator;
        $this->blockCypher = $blockCipher;
    }

    /**
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(SerializeEvent::INJECT, array($this, 'decrypt'), 500);
        $this->listeners[] = $events->attach(SerializeEvent::EXTRACT, array($this, 'encrypt'), -500);
    }

    public function decrypt(SerializeEvent $event)
    {
        $subject = $event->getSubject();
        $data = &$event->getData();

        $this->locator->locate(get_class($subject))->decrypt($data, $this->blockCypher);
    }

    public function encrypt(SerializeEvent $event)
    {
        $subject = $event->getSubject();
        $data = &$event->getData();

        $this->locator->locate(get_class($subject))->encrypt($data, $this->blockCypher);
    }
}

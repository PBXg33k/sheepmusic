<?php
namespace BlackSheep\MusicLibraryBundle\Dispatcher;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Dispatch events on Kernel Terminate
 */
class DelayedEventDispatcher implements EventDispatcherInterface, EventSubscriberInterface
{
    /**
     *  Event Dispatcher
     *
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Queued events
     *
     * @var array
     */
    private $queue;

    /**
     * Is the dispatcher ready to dispatch events?
     *
     * @var boolean
     */
    private $ready;

    /**
     * The Deleyad event dispatcher wraps another dispatcher
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->queue = [];
        $this->ready = false;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::TERMINATE => 'setReady'];
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($eventName, Event $event = null)
    {
        if (!$this->ready) {
            $this->queue[] = ['name' => $eventName, 'instance' => $event];

            return $event;
        }

        return $this->dispatcher->dispatch($eventName, $event);
    }

    /**
     * Set ready
     */
    public function setReady()
    {
        if (!$this->ready) {
            $this->ready = true;

            while ($event = array_shift($this->queue)) {
                $this->dispatcher->dispatch($event['name'], $event['instance']);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function addListener($eventName, $listener, $priority = 0)
    {
        $this->dispatcher->addListener($eventName, $listener, $priority);
    }

    /**
     * @inheritDoc
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->addSubscriber($subscriber);
    }

    /**
     * @inheritDoc
     */
    public function removeListener($eventName, $listener)
    {
        $this->dispatcher->removeListener($eventName, $listener);
    }

    /**
     * @inheritDoc
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->dispatcher->removeSubscriber($subscriber);
    }

    /**
     * @inheritDoc
     */
    public function getListeners($eventName = null)
    {
        return $this->dispatcher->getListeners($eventName);
    }

    /**
     * @inheritDoc
     */
    public function getListenerPriority($eventName, $listener)
    {
        return $this->dispatcher->getListenerPriority($eventName, $listener);
    }

    /**
     * @inheritDoc
     */
    public function hasListeners($eventName = null)
    {
        return $this->dispatcher->hasListeners($eventName);
    }
}

<?php


namespace AppBundle\EventSubscriber;


use AppBundle\Service\MessageManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AddNiceHeaderEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var MessageManager
     */
    private $messageManager;
    /**
     * @var
     */
    private $showDiscouragingMessage;

    /**
     * AddNiceHeaderEventSubscriber constructor.
     */
    public function __construct(LoggerInterface $logger, MessageManager $messageManager, $showDiscouragingMessage)
    {
        $this->logger = $logger;
        $this->messageManager = $messageManager;
        $this->showDiscouragingMessage = $showDiscouragingMessage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse'
        ];
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->logger->info('Adding a nice header!');

        $message = $this->showDiscouragingMessage
            ? $this->messageManager->getDiscouragingMessage()
            : $this->messageManager->getEncouragingMessage();

        $event->getResponse()
            ->headers->set('X-NICE-MESSAGE', $message);
    }
}
<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

/**
 * Exposes the browser's Save-Data preference as a Twig global.
 *
 * Sets the {{ save_data }} variable to true when the request contains the
 * "Save-Data: on" Client Hint header so that templates can opt-in to
 * lighter markup (e.g. skip decorative images, use smaller placeholders).
 */
class SaveDataSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly Environment $twig)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $saveData = strtolower(
            (string) $event->getRequest()->headers->get('Save-Data', '')
        ) === 'on';

        $this->twig->addGlobal('save_data', $saveData);
    }
}

<?php

namespace App\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;


class LocaleSubscriber implements EventSubscriberInterface
{

    private $supportedLanguages = ['fr','fr-FR'];

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if ($event->getRequest()->getPathInfo() !== '/') {
            return;
        }

        $best = null;

        if (null !== $request->headers->get('Accept-Language')) {
            $negotiator = new \Negotiation\LanguageNegotiator();
            $best       = $negotiator->getBest(
                $request->headers->get('Accept-Language'),
                $this->supportedLanguages
            );
        }

        if($best === null){
            return;
        }

        $request->setLocale($this->supportedLanguages[1]);
    }
    public static function getSubscribedEvents(){
        return [
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}

<?php


namespace App\Services;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionServices
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function setSession()
    {
        $this->session->start();
        $this->session->set('itemNumber', '0');
    }

    public function getSession()
    {
        return $this->session->get('itemNumber');
    }

    public function updateSession($qty)
    {
        $this->session->start();
        $this->session->set('itemNumber', $qty);
    }
}

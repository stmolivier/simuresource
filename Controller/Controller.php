<?php

namespace CPASimUSante\SimuResourceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use CPASimUSante\SimuResourceBundle\Event\Log\LogSimuResourceEditEvent;

/**
 * Class Controller
 * @package CPASimUSante\SimuresourceBundle\Controller
 *
 * helper class, not mandatory in every project
 */
class Controller extends BaseController
{

    protected function dispatch($event)
    {
        if (
            $event instanceof LogSimuResourceEditEvent
        ) {
            // Other logs are WIP.
            $this->get('event_dispatcher')->dispatch('log', $event);
        }
        return $this;
    }

    protected function getSecurityContext()
    {
        return $this->get("security.context");
    }

    protected function isUserGranted($action, $data)
    {
        return $this->getSecurityContext()->isGranted($action, $data);
    }

    protected function checkUserGranted($action, $data)
    {
        if (!$this->isUserGranted($action, $data))
        {
            throw new AccessDeniedException;
        }
    }

    protected function checkAdmin()
    {
        if ($this->getSecurityContext()->isGranted('ROLE_ADMIN')) {
            return true;
        }
        throw new AccessDeniedException();
    }

    protected function getFactory()
    {
        return $this->get("form.factory");
    }
}
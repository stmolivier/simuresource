<?php

namespace CPASimUSante\SimuResourceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as EXT;

class SimuResourceController extends Controller
{
    /**
     * @EXT\Route("/index", name="cpasimusante_simuresource_index")
     * @EXT\Template
     *
     * @return Response
     */
    public function indexAction()
    {
        throw new \Exception('hello');
    }
}

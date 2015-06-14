<?php

namespace CPASimUSante\SimuresourceBundle\Manager;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\FormFactoryInterface;
use Claroline\CoreBundle\Persistence\ObjectManager;
use CPASimUSante\SimuresourceBundle\Entity\Simuresource;
use CPASimUSante\SimuresourceBundle\Repository\SimuResourceRepository;
use Symfony\Component\HttpFoundation\Request;


/**
 *
 */
class SimuResourceManager
{
    /**
     * @var object object manager
     */
    private $om;

    private $em;
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    /**
     * @DI\InjectParams({
     *      "em"                    = @DI\Inject("doctrine.orm.entity_manager"),
     *      "formFactory"           = @DI\Inject("form.factory"),
     *      "om"                    = @DI\Inject("claroline.persistence.object_manager")
     * })
     * @param EntityManager $em
     * @param FormFactoryInterface $formFactory
     * @param ObjectManager $om
     */
    public function __construct(
        EntityManager $em,
        FormFactoryInterface $formFactory,
        ObjectManager $om
    ) {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->om = $om;
    }

    /**
     * Retrieve a Resource config
     *
     * @return mixed
     */
    public function getResourceConfig(SimuResource $simuresource)
    {
        $resourceconfig = $this->em->getRepository("CPASimUSanteSimuResourceBundle:SimuResource")->findOneBy(
            array('id' => $simuresource->getId())
        );
        //no config set
        if (sizeof($resourceconfig) == 0)
        {
            return new SimuResource();
        }
        else
        {
            return $resourceconfig[0];
        }
    }

    /**
     * @param SimuResource $resourceconfig
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getResourceConfigForm(SimuResource $resourceconfig = null)
    {
        $resourceconfig = $this->getResourceConfig();
        $form = $this->formFactory->create(
            'cpasimusante_simuresourcebundle_simuresource',    //factory name (in CPASimUSante\SimuresourceBundle\Form\SimuResourceType)
            $resourceconfig
        );
        return $form;
    }

    /**
     * Configuration form process
     *
     * @param SimuResource $resourceconfig
     * @param Request $request
     * @return SimuResource
     */
    public function processForm(SimuResource $resourceconfig, Request $request)
    {
        $form = $this->getResourceConfigForm($resourceconfig);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $resourceconfig = $form->getData();
            $this->em->persist($resourceconfig);
            $this->em->flush();
            return $resourceconfig;
        }
    }
}
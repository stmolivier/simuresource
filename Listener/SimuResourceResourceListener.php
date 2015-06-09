<?php

namespace CPASimUSante\SimuResourceBundle\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Claroline\CoreBundle\Event\CopyResourceEvent;
use Claroline\CoreBundle\Event\CreateFormResourceEvent;
use Claroline\CoreBundle\Event\CreateResourceEvent;
use Claroline\CoreBundle\Event\OpenResourceEvent;
use Claroline\CoreBundle\Event\DeleteResourceEvent;
use Claroline\CoreBundle\Event\DownloadResourceEvent;
use Claroline\CoreBundle\Event\CustomActionResourceEvent;
use Claroline\CoreBundle\Event\ExportResourceTemplateEvent;
use Claroline\CoreBundle\Event\ImportResourceTemplateEvent;
use CPASimUSante\SimuResourceBundle\Entity\SimuResource;
use CPASimUSante\SimuResourceBundle\Form\SimuResourceType;

/**
 *  @DI\Service()
 */
class SimuResourceResourceListener
{
    private $container;

    /**
     * @DI\InjectParams({
     *     "container" = @DI\Inject("service_container")
     * })
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @DI\Observe("create_form_cpasimusante_simuresource")
     *
     * @param CreateFormResourceEvent $event
     */
    public function onCreateForm(CreateFormResourceEvent $event)
    {
        $form = $this->container->get('form.factory')->create(new SimuResourceType(), new SimuResource());
        $content = $this->container->get('templating')->render(
            'CPASimUSanteSimuResourceBundle:SimuResource:createForm.html.twig',
            array(
                'form' => $form->createView(),
                'resourceType' => 'cpasimusante_simuresource'
            )
        );
        $event->setResponseContent($content);
        $event->stopPropagation();
    }

    /**
     * @DI\Observe("create_cpasimusante_simuresource")
     *
     * @param CreateResourceEvent $event
     */
    public function onCreate(CreateResourceEvent $event)
    {
        $request = $this->container->get('request');
        $form = $this->container->get('form.factory')->create(new SimuResourceType(), new SimuResource());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $published = $form->get('published')->getData();
            $event->setPublished($published);
            $event->setResources(array($form->getData()));
            $event->stopPropagation();

            return;
        }

        $content = $this->container->get('templating')->render(
            'CPASimUSanteSimuResourceBundle:SimuResource:createForm.html.twig',
            array(
                'form' => $form->createView(),
                'resourceType' => $event->getResourceType()
            )
        );
        $event->setErrorFormContent($content);
        $event->stopPropagation();
    }

    /**
     * @DI\Observe("delete_cpasimusante_simuresource")
     *
     * @param DeleteResourceEvent $event
     */
    public function onDelete(DeleteResourceEvent $event)
    {
        $event->stopPropagation();
    }

    /**
     * @DI\Observe("copy_cpasimusante_simuresource")
     *
     * @param CopyResourceEvent $event
     */
    public function onCopy(CopyResourceEvent $event)
    {
        $newRes = null;
        $event->setCopy($newRes);
        $event->stopPropagation();
    }

    /**
     * @DI\Observe("download_cpasimusante_simuresource")
     *
     * @param DownloadResourceEvent $event
     */
    public function onDownload(DownloadResourceEvent $event)
    {
        $path = '/path/to/dledfile';
        $event->setItem($path);
        $event->stopPropagation();
    }

    /**
     * @DI\Observe("open_cpasimusante_simuresource")
     *
     * @param OpenResourceEvent $event
     */
    public function onOpen(OpenResourceEvent $event)
    {
        $response = null;
        $event->setResponse($response);
        $event->stopPropagation();
    }
}

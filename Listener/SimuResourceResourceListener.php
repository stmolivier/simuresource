<?php

namespace CPASimUSante\SimuResourceBundle\Listener;

use JMS\DiExtraBundle\Annotation as DI;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Claroline\CoreBundle\Event\CopyResourceEvent;
use Claroline\CoreBundle\Event\CreateFormResourceEvent;
use Claroline\CoreBundle\Event\CreateResourceEvent;
use Claroline\CoreBundle\Event\OpenResourceEvent;
use Claroline\CoreBundle\Event\DeleteResourceEvent;
use Claroline\CoreBundle\Event\DownloadResourceEvent;
use Claroline\CoreBundle\Event\CustomActionResourceEvent;
use Claroline\CoreBundle\Event\ExportResourceTemplateEvent;
use Claroline\CoreBundle\Event\ImportResourceTemplateEvent;
use Claroline\CoreBundle\Event\PluginOptionsEvent;

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
    //-------------------------------
    // PLUGIN GENERAL SETTINGS
    //-------------------------------

    /**
     * @DI\Observe("plugin_options_simuresourcebundle")
     *
     * @param PluginOptionsEvent $event
     */
    public function onPluginConfigure(PluginOptionsEvent $event)
    {
        /*//retrieve the plugin manager with its Service name
        $pluginManager = $this->container->get("cpasimusante.plugin.manager.pluginconfig");
        $form = $pluginManager->getPluginconfigForm();
        //Send the form to the renderer
        $content = $this->templating->render(
            'CPASimUSanteSimutoolsBundle:Tools:pluginconfig.html.twig',
            array(
                'form' => $form->createView()
            )
        );*/
        $content = "resource";
        //PluginOptionsEvent require a setResponse()
        $event->setResponse(new Response($content));
        $event->stopPropagation();
    }

    //-------------------------------
    // RESOURCE SETTINGS
    //-------------------------------
    /**
     * Display the form when adding a new SimuResource in the Resource section
     */
    /**
     * @DI\Observe("create_form_cpasimusante_simuresource")
     *
     * @param CreateFormResourceEvent $event
     */
    public function onCreateForm(CreateFormResourceEvent $event)
    {
        $resource = new SimuResource();
        $resource->setOtherfield(44);
        $form = $this->container->get('form.factory')
            ->create(new SimuResourceType(), $resource);
        $content = $this->container->get('templating')->render(
            //use this one if i want to override the generic template : 'ClarolineCoreBundle:Resource:createForm.html.twig',
            //i.e : the generic template displays all fields
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
     * When creation form is sent, what is done ?
     */
    /**
     * @DI\Observe("create_cpasimusante_simuresource")
     *
     * @param CreateResourceEvent $event
     */
    public function onCreate(CreateResourceEvent $event)
    {
        $request = $this->container->get('request');
        $form = $this->container->get('form.factory')
            ->create(new SimuResourceType(), new SimuResource());

        $form->handleRequest($request);
        if ($form->isValid()) {

            $em = $this->container->get('doctrine.orm.entity_manager');

            $resource = $form->getData();
            //the claroline Resource needs a name, we set it with whatever we have
            $resource->setName($resource->getField());

            $em->persist($resource);

            $event->setResources(array($resource));
            $event->stopPropagation();
            //exit the modal
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
        //In case other entities are dependant from this one, do the stuff before (delete or move ...)
        $event->stopPropagation();
    }

    /**
     * @DI\Observe("copy_cpasimusante_simuresource")
     *
     * @param CopyResourceEvent $event
     */
    public function onCopy(CopyResourceEvent $event)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $resource = $event->getResource();
        //Retrieve the entity (as an object) from the repository
        $resourceNow = $em->getRepository('CPASimUSanteSimuResourceBundle:SimuResource')->find($resource->getId());

        //Copy the entity into a new one
        $resourceNew = new SimuResource();
        //custom entity fields
        $resourceNew->setField($resourceNow->getField());
        $resourceNew->setOtherfield($resourceNow->getOtherfield());
        //generic entity fields
        $resourceNew->setName($resourceNow->getName());
        //Save the entity
        $em->persist($resourceNew);
        $em->flush();
        //Set the copy (Claroline stuff)
        $event->setCopy($resourceNew);
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
        //Redirection to the controller.
        $route = $this->container
            ->get('router')
            ->generate('cpasimusante_simuresource_resource_open', array('simuresourceId' => $event->getResource()->getId()));
        $response = new RedirectResponse($route);
        $event->setResponse($response);
        $event->stopPropagation();
    }

    /**
     * custom menu item for resource, defined in Resources/config/config.ylm
     */
    /**
     * @DI\Observe("dostuff_cpasimusante_simuresource")
     *
     * @param CustomActionResourceEvent $event
     */
    public function onDostuff(CustomActionResourceEvent $event)
    {
        $content = $this->container->get('templating')->render(
            'CPASimUSanteSimuResourceBundle:SimuResource:dostuff.html.twig',
            array(
            )
        );
        $response = new Response($content);
        $event->setResponse($response);
        $event->stopPropagation();
    }
}

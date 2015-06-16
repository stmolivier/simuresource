<?php

namespace CPASimUSante\SimuResourceBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as EXT;

//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormFactory; //for doinmodal()
use Claroline\CoreBundle\Library\Resource\ResourceCollection;
use Claroline\CoreBundle\Entity\Resource\ResourceNode;

use CPASimUSante\SimuResourceBundle\Controller\Controller;
use CPASimUSante\SimuResourceBundle\Manager\SimuResourceManager;
use CPASimUSante\SimuResourceBundle\Entity\SimuResource;
//if we edit the resource data
//use CPASimUSante\SimuResourceBundle\Form\SimuResourceType;
use CPASimUSante\SimuResourceBundle\Form\SimuResourceEditType;
use Symfony\Component\HttpFoundation\JsonResponse;

class SimuResourceController extends Controller
{
    private $simuresourceManager;
    private $request;
    /**
     * @DI\InjectParams({
     *      "simuresourceManager"   = @DI\Inject("cpasimusante.plugin.manager.simuresource"),
     *     "requestStack"       = @DI\Inject("request_stack")
     * })
     * @param SimuResourceManager $simuresourceManager
     */
    public function __construct(
        SimuResourceManager $simuresourceManager,
        RequestStack $requestStack
    )
    {
        $this->simuresourceManager = $simuresourceManager;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @EXT\Route("/update", name="cpasimusante_simuresource_pluginconfig_update")
     * @EXT\Method({"GET", "POST"})
     * the template for the form
     * @EXT\Template("CPASimUSanteSimuresourceBundle:SimuResource:pluginconfig.html.twig")
     *
     * @param Request $request
     * @return Response
     */
    public function updateAction(Request $request)
    {
        //only admin access
        $this->checkAdmin();
        //retrieve the resource config
        $resourceconfig = $this->simuresourceManager->getResourceConfig();
        //process the resource
        $resourceconfig = $this->simuresourceManager->processForm($resourceconfig, $request);
        //redirect when validated
        $response = $this->forward(
            "CPASimUSanteSimuresourceBundle:Simuresource:success",
            array(
                'resourceconfig' => $resourceconfig
            )
        );

        return $response;
    }

    //-------------------------------
    // METHODS FOR CUSTOM LISTENER METHODS
    //-------------------------------
    /**
     * Called on onDoinmodal Listener method for form POST
     * @EXT\Route(
     *     "/edit/{node}",
     *     name="cpasimusante_simuresource_edit_form",
     *     options={"expose"=true}
     * )
     *
     * @EXT\Template("CPASimUSanteSimuResourceBundle:SimuResource:doinmodal.html.twig")
     *
     * @param SimuResource $resourceInstance
     *
     * @return array
     */
    public function doinmodal(ResourceNode $node)
    {
        $resourceconfig = $this->simuresourceManager->getResourceConfigByNode($node->getId());

        $form = $this->getFactory()->create(
            new SimuResourceEditType(),
            $resourceconfig
        );

        return array(
            'form' => $form->createView(),
            'node' => $node
        );
    }

    /**
     * What to do when doinmodal listener form is sent
     */
    /**
     * @EXT\Route(
     *     "/change/{node}",
     *     name="doinmodal_change",
     *     options={"expose"=true}
     * )doinmodal
     *
     * @EXT\Template("CPASimUSanteSimuResourceBundle:SimuResource:doinmodal.html.twig")
     */
    public function doinmodalAction(ResourceNode $node)
    {
        if (!$this->isUserGranted('edit', $node)){
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $simuresource = $em->getRepository('CPASimUSanteSimuResourceBundle:SimuResource')
            ->findOneBy(array('resourceNode' => $node->getId()));

        if (!$simuresource){
            throw new \Exception("This resource doesn't exist.");
        }

        $form = $this->getFactory()->create(new SimuResourceEditType(), $simuresource);
        $form->handleRequest($this->request);

        if ($form->isValid()){
            $sr = $form->getData();
            $em->persist($sr);
            $em->flush();

            return new JsonResponse();
        }

        return array('form' => $form->createView(), 'node' => $node->getId());

    }

    /**
     * Called on onConfigure Listener method for form POST
     * @param WidgetInstance $widgetInstance
     * @return array    AJAX response
     */
    /**
     * @EXT\Route(
     *     "/userwidget/widget/{widgetInstance}/configure/form",
     *     name="simusante_userwidget_configure_form",
     *     options={"expose"=true}
     * )
     * @EXT\ParamConverter("authenticatedUser", options={"authenticatedUser" = true})
     * @EXT\Template("SimusanteUserwidgetBundle:Widget:userwidgetConfigureForm.html.twig")
     */


    /**
     * Update the SimuResource elements
     */
    /**
     * @EXT\Route("/update/{simuresource}/form", name="cpasimusante_simuresource_update_form", options = {"expose" = true})
     *
     * @EXT\Template()
     */
    public function updateSimuResourceFormAction(SimuResource $simuresource)
    {
        $collection = new ResourceCollection(array($simuresource->getResourceNode()));
        if (!$this->get('security.authorization_checker')->isGranted('EDIT', $collection)) {
            throw new AccessDeniedException($collection->getErrorsForDisplay());
        }
        $form = $this->get('form.factory')->create(new SimuResourceEditType(), new SimuResource());

        return array(
            'form' => $form->createView(),
            'resourceType' => 'cpasimusante_simuresource',
            'file' => $simuresource,
            '_resource' => $simuresource
        );
    }

    /**
     *
     */
    /**
     * @EXT\Route("/update/{simuresource}", name="cpasimusante_simuresource_update", options = {"expose" = true})
     *
     * @EXT\Template("CPASimUSanteSimuresourceBundle:SimuResource:updateSimuresourceForm.html.twig")
     */
    public function updateSimuResourceAction(SimuResource $simuresource)
    {
        $collection = new ResourceCollection(array($simuresource->getResourceNode()));
        if (!$this->get('security.authorization_checker')->isGranted('EDIT', $collection)) {
            throw new AccessDeniedException($collection->getErrorsForDisplay());
        }
        $request = $this->get('request');
        $form = $this->get('form.factory')->create(new SimuResourceEditType(), new SimuResource());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $tmpFile = $form->get('simuresource')->getData();

            if ($this->get('claroline.twig.home_extension')->isDesktop()) {
                $arrayOptions = array('toolName' => 'resource_manager');
            } else {
                $arrayOptions = array(
                        'toolName' => 'resource_manager',
                        'workspaceId' => $simuresource->getResourceNode()->getWorkspace()->getId()
                );
            }
            $url = $this->generateUrl('claro_desktop_open_tool', $arrayOptions);

            return $this->redirect($url);
        }

        return array(
            'form' => $form->createView(),
            'resourceType' => 'cpasimusante_simuresource',
            'file' => $simuresource,
            '_resource' => $simuresource
        );
    }

    /**
     * @EXT\Route("/form/edit/{simuresource}", name="cpasimusante_simuresource_edit_form2")
     * the template for the form
     * @EXT\Template()
     *
     */
    public function editFormAction(SimuResource $simuresource)
    {
        //Check access
        $collection = new ResourceCollection(array($simuresource->getResourceNode()));
      //  $this->checkAccess('EDIT', $collection);

        return array(
            '_resource' => $simuresource
        );
    }

    /**
     * @EXT\Route("/update/success", name="cpasimusante_simuresource_pluginconfig_update_success")
     * @EXT\Method({"GET"})
     * @EXT\Template("CPASimUSanteSimuresourceBundle:SimuResource:pluginconfigsuccess.html.twig")
     *
     * @param Pluginconfig $pluginconfig
     * @return array
     */
    public function successAction(Pluginconfig $pluginconfig = null)
    {
        $this->checkAdmin();
        //parm to be returned
        return array(
            'pluginconfig' => $pluginconfig
        );
    }


    /**
     * @EXT\Route("/open/{simuresourceId}", name="cpasimusante_simuresource_resource_open")
     * template to be displayed
     * @EXT\Template("CPASimUSanteSimuResourceBundle:SimuResource:resourceopen.html.twig")
     *
     * @param integer $simuresourceId id of simuresource
     * @return array parameters to send to the template
     */
    public function openAction($simuresourceId)
    {
        $em = $this->getDoctrine()->getManager();
        //retrieve the resource (object)
        $resource = $em->getRepository('CPASimUSanteSimuResourceBundle:SimuResource')->find($simuresourceId);

        //retrieve the user
        $user = $this->container->get('security.token_storage')
            ->getToken()->getUser();
        if (is_object($user)) {
            $uid = $user->getId();
        } else {
            $uid = 'anonymous';
        }

        //retrieve the WS
        $workspace = $resource->getResourceNode()->getWorkspace();

        $collection = new ResourceCollection(array($resource->getResourceNode()));
        //check the user authorization to edit
        $isGranted = $this->container->get('security.authorization_checker')->isGranted('EDIT', $collection);

        return array(
            'entity'        => $resource,
            'userId'        => $uid,
            'workspace'     => $workspace,
            '_resource'     => $resource,    //mandatory to keep the context and display for instance the breadcrumb in the template
            'isEditGranted' => $isGranted
        );
    }
}

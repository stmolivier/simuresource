<?php

namespace CPASimUSante\SimuResourceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as EXT;

class SimuResourceController extends Controller
{
    /**
     * @EXT\Route("/update", name="cpasimusante_simuresource_pluginconfig_update")
     * @EXT\Method({"GET", "POST"})
     * the template dor the form
     * @EXT\Template("CPASimUSanteSimuresourceBundle:SimuResource:pluginconfig.html.twig")
     *
     * @param Request $request
     * @return Response
     */
    public function updateAction(Request $request)
    {
        //only admin access
        $this->checkAdmin();
        //retrieve the plugin config
        $pluginconfig = $this->pcManager->getPluginconfig();

        try {
            $pluginconfig = $this->pcManager->processForm($pluginconfig, $request);
        } catch (InvalidPluginconfigFormException $e) {
            return array('form' => $e->getForm()->createView());
        }
        //redirect when validated
        $response = $this->forward(
            "CPASimUSanteSimuresourceBundle:Simuresource:success",
            array(
                'pluginconfig' => $pluginconfig
            )
        );

        return $response;
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

        return array(
            'entity'        => $resource,
            'userId'        => $uid,
            'workspace'     => $workspace
        );
    }
}

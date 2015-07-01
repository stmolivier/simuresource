<?php

namespace CPASimUSante\SimuResourceBundle\Manager;

use JMS\DiExtraBundle\Annotation as DI;
use Claroline\CoreBundle\Manager\MaskManager;
use Claroline\CoreBundle\Entity\Resource\ResourceNode;

/**
 * @DI\Service("cpasimusante.plugin.manager.general")
 */
class GeneralManager
{
    private $container;
    private $maskManager;
    private $em;

    /**
     * @DI\InjectParams({
     *     "container" = @DI\Inject("service_container"),
     *     "maskManager" = @DI\Inject("claroline.manager.mask_manager"),
     *     "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct($container, MaskManager $maskManager, $em)
    {
        $this->container = $container;
        $this->maskManager = $maskManager;
        $this->em = $em;
    }

    /**
     *  Getting the users (id) that have the $selectedright rights
     *  Excluded the admin profil.
     * For a resource
     *
     * @return array UserIds.
     */
    public function getUsersIdsForResourceByRights(ResourceNode $node, $selectedright='open',$excludeAdmin=true)
    {
//        $this->container->get('icap.manager.dropzone_voter')->isAllowToEdit($dropzone);

        //getting the ressource node
     //   $ressourceNode = $dropzone->getResourceNode();
        // getting the rights of the ressource node
        $rights = $node->getRights();

        // will contain the user's ids.
        $userIds = array();
        $test = array();
        // searching for roles with the 'open' right
        foreach ($rights as $ressourceRight) {
            $role = $ressourceRight->getRole(); //  current role
            $mask = $ressourceRight->getMask(); // current mask

            // getting decoded rights.
            $decodedRights = $this->maskManager->decodeMask($mask, $node->getResourceType());
            $checkrights = (array_key_exists($selectedright, $decodedRights) && $decodedRights[$selectedright] == true);
            // if this role is allowed to $selectedright (and this role is not an Admin role)
            $checkrights = ($excludeAdmin) ? $checkrights && ($role->getName() != 'ROLE_ADMIN') : $checkrights;

            if ($checkrights) {
                // the role has the $selectedright right
                array_push($test, $role->getName());
                $users = $role->getUsers();
                foreach ($users as $user) {
                    array_push($userIds, $user->getId());
                }
            }
        }
        $userIds = array_unique($userIds);

        return $userIds;
    }
    /**
     *  Getting the users that have the $selectedright rights
     *  Excluded the admin profil.
     * For a resource
     *
     * @return array UserIds array of user objects|array
     */
    public function getUsersForResourceByRights(ResourceNode $node, $selectedright='open',$excludeAdmin=true)
    {
//        $this->container->get('icap.manager.dropzone_voter')->isAllowToEdit($dropzone);

        //getting the ressource node
        //   $ressourceNode = $dropzone->getResourceNode();
        // getting the rights of the ressource node
        $rights = $node->getRights();

        // will contain the user's ids.
        $userIds = array();
        $test = array();
        // searching for roles with the 'open' right
        foreach ($rights as $ressourceRight) {
            $role = $ressourceRight->getRole(); //  current role
            $mask = $ressourceRight->getMask(); // current mask

            // getting decoded rights.
            $decodedRights = $this->maskManager->decodeMask($mask, $node->getResourceType());
            $checkrights = (array_key_exists($selectedright, $decodedRights) && $decodedRights[$selectedright] == true);
            // if this role is allowed to $selectedright (and this role is not an Admin role)
            $checkrights = ($excludeAdmin) ? $checkrights && ($role->getName() != 'ROLE_ADMIN') : $checkrights;

            if ($checkrights) {
                $users = $role->getUsers();
                foreach ($users as $user) {
                    array_push($userIds, $user);
                }
            }
        }
        //$users = array_unique($users);

        return $userIds;
    }
}
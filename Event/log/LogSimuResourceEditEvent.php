<?php

namespace CPASimUSante\SimuResourceBundle\Event\Log;

use Claroline\CoreBundle\Event\Log\AbstractLogResourceEvent;
use Claroline\CoreBundle\Event\Log\LogGenericEvent;
use Claroline\CoreBundle\Event\Log\NotifiableInterface;
use CPASimUSante\SimuResourceBundle\Entity\SimuResource;

/**
 * Custom Log event for the bundle, here, used in Controller
 *
 * Class LogSimuResourceEditEvent
 * @package CPASimUSante\SimuResourceBundle\Event\Log
 */
class LogSimuResourceEditEvent
    extends AbstractLogResourceEvent    //log associated to a resource, or
    //extends AbstractLogToolEvent      //log associated to a tool, or
    //extends AbstractLogWidgetEvent    //log associated to a widget
    implements NotifiableInterface {

    //Constant, mandatory, to define the specific action in the log
    //name in 3 parts separated by dashes, each part may have underscore in them :
    // - type of object it's associated to (resource, platform, workspace, user, role, tools, widget..)
    // - type of the resource (optional)    here, cpasimusante_simuresource => name in config.yml
    // - action executed (login, post_create, whatever...)
    const ACTION = 'resource-cpasimusante_simuresource-simuresource_edit';
    //Other paramteters sent to the log
    protected $simuresource;
    protected $details;
    private $userIds = array();

    /**
     *
     */
    public function __construct(SimuResource $simuresource, $userIds)
    {
        $this->simuresource = $simuresource;
        $this->userIds = $userIds;
        $this->details = array(
            'newState'=> 'xxSome stuffxx'
        );

        parent::__construct($simuresource->getResourceNode(), $this->details);
    }

    /**
     * Where to display the log. By default, not shown on visualization interface
     *
     * @return array
     */
    public static function getRestriction()
    {
        return array(self::DISPLAYED_WORKSPACE);
        //return array(self::DISPLAYED_ADMIN); //other choice
    }

    public function getSimuResource()
    {
        return $this->simuresource;
    }

    /**
     * Get sendToFollowers boolean.
     *
     * @return boolean
     */
    public function getSendToFollowers()
    {
        return true;
    }

    /**
     * Get includeUsers array of user ids.
     * Reports are only reported to user witch have the manager role
     * @return array
     */
    public function getIncludeUserIds()
    {
        return $this->userIds;
    }

    /**
     * Get excludeUsers array of user ids.
     *
     * @return array
     */
    public function getExcludeUserIds()
    {
        return array();
    }

    /**
     * Get actionKey string.
     *
     * @return string
     */
    public function getActionKey()
    {
        return $this::ACTION;
    }

    /**
     * Get iconTypeUrl string.
     *
     * @return string
     */
    public function getIconKey()
    {
        return "simuresource";
    }

    /**
     * Get details
     *
     * @return array
     */
    public function getNotificationDetails()
    {
        $notificationDetails = array_merge($this->details, array());
        $notificationDetails['resource'] = array(
            'id' => $this->simuresource->getId(),
            'name' => $this->resource->getName(),
            'type' => $this->resource->getResourceType()->getName()
        );

        return $notificationDetails;
    }

    /**
     * Get if event is allowed to create notification or not
     *
     * @return boolean
     */
    public function isAllowedToNotify()
    {
        return true;
    }
}
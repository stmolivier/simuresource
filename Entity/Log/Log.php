<?php
/*
 * Override of CoreBundle Log Entity to be able to use the overriden LogRepository
 */
namespace CPASimUSante\SimuResourceBundle\Entity\Log;

use Doctrine\ORM\Mapping as ORM;
use Claroline\CoreBundle\Entity\Log\Log as BaseEntity;

/**
 * @ORM\Entity(repositoryClass="CPASimUSante\SimuResourceBundle\Repository\Log\LogRepository")
 * @ORM\Table(name="claro_log")
 */
class Log extends BaseEntity
{

}

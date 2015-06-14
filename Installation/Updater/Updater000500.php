<?php
namespace CPASimUSante\SimuResourceBundle\Installation\Updater;

use Claroline\InstallationBundle\Updater\Updater;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManager;

class UpdaterCPA000500 extends Updater
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postUpdate()
    {

    }

}
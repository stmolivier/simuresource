<?php

namespace CPASimUSante\SimuResourceBundle\Installation\Updater;

use Claroline\InstallationBundle\Updater\Updater;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Version;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CPAMigrationUpdater extends Updater
{
    private $container;
    private $conn;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->conn = $container->get('database_connection');
        $this->em = $container->get('doctrine.orm.entity_manager');
    }

    public function preInstall()
    {

    }

    public function postInstall()
    {

    }
}

<?php

namespace CPASimUSante\SimuResourceBundle\Installation;

use Claroline\InstallationBundle\Additional\AdditionalInstaller as BaseInstaller;
use CPASimUSante\SimuResourceBundle\Installation\Updater\UpdaterCPA000500;

class AdditionalInstaller extends BaseInstaller
{
    private $logger;

    public function __construct()
    {
        $self = $this;
        $this->logger = function ($message) use ($self) {
            $self->log($message);
        }
    }

    public function preInstall()
    {
        $updater = new Updater\CPAMigrationUpdater($this->container);
        $updater->setLogger($this->logger);
        $updater->preInstall();
    }

    public function postInstall()
    {
        $updater = new Updater\CPAMigrationUpdater($this->container);
        $updater->setLogger($this->logger);
        $updater->postInstall();

        $updater000500 = new UpdaterCPA000500($this->container->get('doctrine.orm.entity_manager'), $this->container->get('doctrine.dbal.default_connection'));
        $updater000500->setLogger($this->logger);
        $updater000500->postUpdate();
    }

    public function preUpdate($currentVersion, $targetVersion)
    {
    }

    public function postUpdate($currentVersion, $targetVersion)
    {
        //needs VERSION.txt @ bundle root
        if (version_compare($currentVersion, '0.5', '<')) {
            $updater000500 = new UpdaterCPA000500($this->container->get('doctrine.orm.entity_manager'), $this->container->get('doctrine.dbal.default_connection'));
            $updater000500->setLogger($this->logger);
            $updater000500->postUpdate();
        }
    }
}

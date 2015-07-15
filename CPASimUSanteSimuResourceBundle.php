<?php

namespace CPASimUSante\SimuResourceBundle;

use Claroline\CoreBundle\Library\PluginBundle;
use Claroline\KernelBundle\Bundle\ConfigurationBuilder;
use Claroline\BundleBundle\Installation\AdditionalInstaller;

/**
 * Bundle class.
 * Uncomment if necessary.
 */
class CPASimUSanteSimuResourceBundle extends PluginBundle
{
    public function getConfiguration($environment)
    {
        $config = new ConfigurationBuilder();
        return $config->addRoutingResource(__DIR__ . '/Resources/config/routing.yml', null, 'simuresource');
    }

    /*
    public function getAdditionalInstaller()
    {
        return new AdditionalInstaller();
    }
    */

    public function hasMigrations()
    {
        return true;
    }

    /**
     * get the correct directory containing the fixtures to be installed
     *
     * @param $environment
     * @return string
     */
    public function getRequiredFixturesDirectory($environment)
    {
        return 'DataFixtures';
    }

    public function getParent()
    {
        return 'ClarolineCoreBundle';
    }
}

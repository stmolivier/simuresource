<?php

namespace CPASimUSante\SimuResourceBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CPASimUSante\SimuResourceBundle\Entity\SimuResource;

/**
 * Loaded when the package is installed after the table is created
 *
 * Class LoadOptionsData
 * @package CPASimUSante\SimuResourceBundle\DataFixtures
 */
class LoadSimuResourceData extends AbstractFixture
{
    private $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        //example of initial fixture for the resource entity
        $sr = new SimuResource();
        $sr->setField('bunny');
        $sr->setOtherfield(16);
        $sr->setOtherfield2(28);

        $this->manager->persist($sr);

        $this->manager->flush();
    }

    /**
     * method allowing to order the fixture files if many
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}

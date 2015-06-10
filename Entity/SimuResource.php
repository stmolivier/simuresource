<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CPASimUSante\SimuResourceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Claroline\CoreBundle\Entity\Resource\AbstractResource;

/**
 * @ORM\Table(name="cpasimusante_simuresource")
 * @ORM\Entity(repositoryClass="CPASimUSante\SimuResourceBundle\Repository\SimuResourceRepository")
 */
class SimuResource extends AbstractResource
{
    /**
     * @ORM\Column(name="field_example")
     */
    protected $field = 'data';

    /**
     * @ORM\Column(name="otherfield", type="integer")
     */
    protected $otherfield;

    /**
     * Set field
     *
     * @param string $field
     *
     * @return SimuResource
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set otherfield
     *
     * @param integer $otherfield
     *
     * @return SimuResource
     */
    public function setOtherfield($otherfield)
    {
        $this->otherfield = $otherfield;

        return $this;
    }

    /**
     * Get otherfield
     *
     * @return integer
     */
    public function getOtherfield()
    {
        return $this->otherfield;
    }
}

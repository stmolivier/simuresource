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
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @ORM\Column(name="otherfield2", type="integer")
     */
    protected $otherfield2;

    /**
     * The file uploaded, but not saved in entity/DB => no ORM\Column
     */
    protected $file;

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

    public function getFile() {
        return $this->file;
    }

    public function setFile(UploadedFile $file) {
        $this->file = $file;
        return $this;
    }

    /**
     * Set otherfield2
     *
     * @param integer $otherfield2
     *
     * @return SimuResource
     */
    public function setOtherfield2($otherfield2)
    {
        $this->otherfield2 = $otherfield2;

        return $this;
    }

    /**
     * Get otherfield2
     *
     * @return integer
     */
    public function getOtherfield2()
    {
        return $this->otherfield2;
    }
}

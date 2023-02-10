<?php

namespace Labstag\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * A soft deletable trait you can apply to your Doctrine ORM entities.
 * Includes default annotation mapping.
 *
 * @author Wesley van Opdorp <wesley.van.opdorp@freshheads.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
trait StateableEntity
{

    /**
     * @ORM\Column(type="array")
     */
    protected $state;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="state_changed", type="datetime", nullable=true)
     *
     * @Gedmo\Timestampable(on="change", field={"state"})
     */
    private $stateChanged;

    public function getState()
    {
        return $this->state;
    }

    public function getStateChanged()
    {
        return $this->stateChanged;
    }

    public function setState($state)
    {
        $this->state = $state;
    }
}

<?php

namespace App\TrackerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kf\KitBundle\Doctrine\ORM\Traits as KFT;
use App\Common\Doctrine\ORM\Traits as AppT;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractClient
{
    use AppT\Entity,
        KFT\TimestampableEntity,
        KFT\AuthorableEntity,
        AppT\NameAwareEntity;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="client", orphanRemoval=true)
     */
    protected $activities;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    /**
     * @return Activity[]|ArrayCollection
     */
    public function getActivities()
    {
        return $this->activities;
    }
}
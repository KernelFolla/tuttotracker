<?php

namespace App\TrackerBundle\Entity;

use App\Common\Doctrine\ORM\Traits as AppT;
use Doctrine\ORM\Mapping as ORM;
use Kf\KitBundle\Doctrine\ORM\Traits as KFT;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractActivity
{
    use KFT\Entity,
        KFT\TimestampableEntity,
        KFT\AuthorableEntity,
        AppT\NameAwareEntity,
        AppT\ScheduleAwareEntity
        ;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="activities")
     */
    protected $client;

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }


}
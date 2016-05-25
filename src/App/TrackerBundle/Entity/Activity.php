<?php

namespace App\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="t_activity")
 * @ORM\Entity(repositoryClass="App\TrackerBundle\Repository\ActivityRepository")
 */
class Activity extends AbstractActivity
{
    public function __toString()
    {
        return (string) $this->getName();
    }
}
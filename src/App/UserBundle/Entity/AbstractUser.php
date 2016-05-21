<?php

namespace App\UserBundle\Entity;

use App\ForumBundle\Entity\Traits\PosterEntity;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractUser extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
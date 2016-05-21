<?php

namespace App\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="u_user")
 * @ORM\Entity(repositoryClass="App\UserBundle\Repository\UserRepository")
 */
class User extends AbstractUser
{
}
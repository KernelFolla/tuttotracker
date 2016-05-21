<?php

namespace App\UserBundle\DataFixtures\ORM;

use Kf\KitBundle\Doctrine\Fixtures\AbstractFixture;
use App\UserBundle\Entity as Entity;

class LoadUser extends AbstractFixture
{
    protected $entityClass = Entity\User::class;
}

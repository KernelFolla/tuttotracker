<?php

namespace App\TrackerBundle\DataFixtures\ORM;

use App\UserBundle\DataFixtures\ORM\LoadUser;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Kf\KitBundle\Doctrine\Fixtures\AbstractFixture;
use App\TrackerBundle\Entity as Entity;

class LoadActivity extends AbstractFixture implements DependentFixtureInterface
{
    protected $entityClass = Entity\Activity::class;

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return [
            LoadUser::class,
            LoadClient::class
        ];
    }
}

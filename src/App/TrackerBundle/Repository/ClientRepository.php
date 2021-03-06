<?php

namespace App\TrackerBundle\Repository;

use App\Common\Doctrine\EntityRepository;
use App\TrackerBundle\Entity\Activity;
use App\UserBundle\Entity\User;
use App\UserBundle\Entity\UserDetail;

class ClientRepository extends EntityRepository
{
    const ALIAS = 'client';

    static protected $joinColumns = [
        'activities'       => Activity::class,
        'createdByUser' => User::class,
    ];
    static protected $searchFields = [
        'name'
    ];
    static protected $checkFields = ['id', 'name', 'createdByUser'];
}

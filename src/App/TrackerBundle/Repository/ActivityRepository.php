<?php

namespace App\TrackerBundle\Repository;

use App\Common\Doctrine\EntityRepository;
use App\UserBundle\Entity\UserDetail;

class ActivityRepository extends EntityRepository
{
    const ALIAS = 'activity';

    static protected $joinColumns = [
        'client' => Client::class,
        'createdByUser' => User::class,
    ];
    static protected $searchFields = [
        'name',
    ];
    static protected $checkFields = ['id', 'name', 'createdByUser'];
}

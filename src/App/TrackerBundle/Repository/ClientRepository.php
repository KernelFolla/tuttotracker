<?php

namespace App\TrackerBundle\Repository;

use App\UserBundle\Entity\UserDetail;
use Kf\KitBundle\Doctrine\ORM\Repository\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Kf\KitBundle\Utils\StringUtils;

class ClientRepository extends EntityRepository
{
    const ALIAS = 'client';

    static protected $searchFields = [
        'name'
    ];
    static protected $checkFields = ['id', 'name'];
}

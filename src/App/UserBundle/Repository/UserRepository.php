<?php

namespace App\UserBundle\Repository;

use App\UserBundle\Entity\UserDetail;
use Kf\KitBundle\Doctrine\ORM\Repository\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Kf\KitBundle\Utils\StringUtils;

class UserRepository extends EntityRepository
{
    const ALIAS = 'user';

    static protected $searchFields = [
        'email',
        'username',
    ];
    static protected $checkFields = ['id', 'email', 'username'];

    public function processCriteria(QueryBuilder $query, $criteria = null)
    {
        if (isset($criteria['role'])) {
            if ($criteria['role'] == 'ROLE_USER') {
                $criteria['role'] = 'a:0:{}';
                $query->andWhere($this->getAlias() . '.roles = :' . $this->getAlias() . '_role')
                    ->setParameter($this->getAlias() . '_role', $criteria['role']);
            } else {
                $query->andWhere($this->getAlias() . '.roles like :' . $this->getAlias() . '_role')
                    ->setParameter($this->getAlias() . '_role', '%"' . $criteria['role'] . '"%');
            }
        }
        parent::processCriteria($query, $criteria);
    }
}

<?php

namespace App\Common\Symfony\Form\Model;

use Kf\KitBundle\Doctrine\ORM\Query\FilterInterface;
use App\Common\Model\Traits\WithLimitAndOffset;

abstract class AbstractFilter implements FilterInterface
{
    use WithLimitAndOffset;

    /**
     * @return array
     */
    public function getQueryParameters()
    {
        $ret = ['@orderby' => ['id' => 'DESC']];
        if($x = $this->getOffset()){
            $ret['@offset'] = $x;
        }
        if($x = $this->getLimit()){
            $ret['@limit'] = $x;
        }

        return $ret;
    }

    public function getRouteParameters(){
        $ret = [];
        if($x = $this->getOffset()){
            $ret['offset'] = $x;
        }
        if($x = $this->getLimit()){
            $ret['limit'] = $x;
        }
        return $ret;
    }
}
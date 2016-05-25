<?php

namespace App\TrackerBundle\Form\Model;

use App\Common\Symfony\Form\Model\AbstractFilter;

class ClientFilter extends AbstractFilter
{
    private $s;
    private $user;
    
    /**
     * @return array
     */
    public function getQueryParameters()
    {
        $ret = parent::getQueryParameters();
        if ($this->s) {
            $ret['s'] = $this->s;
        }
        if ($this->user) {
            $ret['createdByUser'] = $this->user->getId();
        }
        return $ret;
    }

    /**
     * @return mixed
     */
    public function getS()
    {
        return $this->s;
    }

    /**
     * @param mixed $s
     */
    public function setS($s)
    {
        $this->s = $s;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}


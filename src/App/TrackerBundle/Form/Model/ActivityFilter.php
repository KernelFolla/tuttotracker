<?php

namespace App\TrackerBundle\Form\Model;

use App\Common\Symfony\Form\Model\AbstractFilter;

class ActivityFilter extends AbstractFilter
{
    private $s;
    private $user;
    private $client;

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

        if ($this->client) {
            $ret['client'] = $this->client->getId();
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

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }


}


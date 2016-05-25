<?php

namespace App\TrackerBundle\Wrapper;

class ActivityWrapper
{
    public $id;
    public $name;
    public $client;
    public $createdBy;
    public $createdAt;
    public $startsAt;
    public $endsAt;

    public function __construct($data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
}
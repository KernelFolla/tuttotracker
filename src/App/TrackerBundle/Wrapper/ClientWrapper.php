<?php

namespace App\TrackerBundle\Wrapper;

class ClientWrapper
{
    public $id;
    public $name;
    public $createdBy;

    public function __construct($data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
}
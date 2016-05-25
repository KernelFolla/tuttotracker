<?php

namespace App\Common\Doctrine\ORM\Traits;

trait ScheduleAwareEntity
{
    /**
     * @var string
     * @\Doctrine\ORM\Mapping\Column(type="datetime", nullable=false)
     * @\Symfony\Component\Validator\Constraints\NotBlank()
     */
    private $startsAt;

    /**
     * @var string
     * @\Doctrine\ORM\Mapping\Column(type="datetime", nullable=true)
     */
    private $endsAt;

    /**
     * @param string $endsAt
     * @return $this
     */
    public function setEndsAt($endsAt)
    {
        $this->endsAt = $endsAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndsAt()
    {
        return $this->endsAt;
    }


    /**
     * @return string
     */
    public function getStartsAt()
    {
        return $this->startsAt;
    }

    /**
     * @param string $startsAt
     * @return $this
     */
    public function setStartsAt($startsAt)
    {
        $this->startsAt = $startsAt;
        return $this;
    }

}

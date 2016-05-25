<?php

namespace App\Common\Doctrine\ORM\Traits;

trait NameAwareEntity
{
    /**
     * @\Doctrine\ORM\Mapping\Column(type="text")
     * @\Symfony\Component\Validator\Constraints\NotBlank()
     */
    private $name;

    /**
     * Set name
     *
     * @param string $name
     * @return Tournament
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

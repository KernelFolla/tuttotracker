<?php

namespace App\Common\Doctrine\ORM\Traits;

trait AuthorAwareEntity {
    /**
     * @\Gedmo\Mapping\Annotation\Blameable(on="create")
     * @\Doctrine\ORM\Mapping\Column(type="string", nullable=true)
     */
    protected $createdBy;

    /**
     * @\Gedmo\Mapping\Annotation\Blameable(on="create")
     * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="\kf_kit_user")
     */
    protected $createdByUser;

    /**
     * Sets createdBy.
     *
     * @param  string $createdBy
     * @return $this
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Returns createdBy.
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Sets createdBy.
     *
     * @param  string $createdBy
     * @return $this
     */
    public function setCreatedByUser($createdBy)
    {
        $this->createdByUser = $createdBy;

        return $this;
    }

    /**
     * Returns createdBy.
     *
     * @return User
     */
    public function getCreatedByUser()
    {
        return $this->createdByUser;
    }
}

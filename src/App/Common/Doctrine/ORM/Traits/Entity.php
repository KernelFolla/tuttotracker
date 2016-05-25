<?php

namespace App\Common\Doctrine\ORM\Traits;

trait Entity
{
    /**
     * @var integer
     *
     * @\Doctrine\ORM\Mapping\Column(name="id", type="integer")
     * @\Doctrine\ORM\Mapping\Id
     * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
     * @\JMS\Serializer\Annotation\Expose
     * @\JMS\Serializer\Annotation\Type("string")
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function equals($entity = null)
    {
        $class = get_class($this);
        return isset($entity) && ($entity instanceof $class  || is_subclass_of($entity,$class))
        && $entity->getID() == $this->getId();
    }
}

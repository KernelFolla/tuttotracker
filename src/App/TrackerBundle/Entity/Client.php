<?php

namespace App\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="t_client")
 * @ORM\Entity(repositoryClass="App\TrackerBundle\Repository\ClientRepository")
 * @Serializer\ExclusionPolicy("none")
 * @UniqueEntity(
 *     fields={"createdByUser", "name"},
 *     errorPath="name",
 *     message="This client already exists."
 * )
 */
class Client extends AbstractClient
{

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @Serializer\HandlerCallback("xml", direction = "serialization")
     * @param JsonSerializationVisitor $visitor
     * @param Client $data
     * @param array $type
     * @param Context $context
     * @return array
     */
    public function serializeToJson(JsonSerializationVisitor $visitor, $data, array $type, Context $context)
    {
        return [
            'id' => $data->getId(),
            'name' => $data->getName()
        ];
    }
}
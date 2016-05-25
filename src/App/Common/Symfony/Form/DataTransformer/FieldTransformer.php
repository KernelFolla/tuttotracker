<?php

namespace App\Common\Symfony\Form\DataTransformer;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FieldTransformer implements DataTransformerInterface
{
    private $field;
    private $accessor;
    private $createCallback;
    private $findCallback;
    private $repo;

    function __construct($field)
    {
        $this->field = $field;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param ObjectRepository $repo
     */
    public function setRepo($repo)
    {
        $this->repo = $repo;
    }


    /**
     * @param mixed $createCallback
     */
    public function setCreateCallback(\Closure $createCallback)
    {
        $this->createCallback = $createCallback;
    }

    /**
     * @param mixed $findCallback
     */
    public function setFindCallback(\Closure $findCallback)
    {
        $this->findCallback = $findCallback;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($entity)
    {
        if (null === $entity) {
            return "";
        }

        return $this->accessor->getValue($entity, $this->field);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }
        try {
            if (isset($this->findCallback)) {
                $func = $this->findCallback;
                $entity = $func($value);
            } elseif (isset($this->repo)) {
                $entity = $this->repo->findOneBy([$this->field => $value]);
            } else {
                new \Exception('transformer not manageable, set a callback or a repo');
            }
        } catch (NonUniqueResultException $x) {
            $ret = new \Exception(sprintf("can't find %s because isn't unique"), 1, $x);

            return $ret;
        }
        if (empty($entity) && isset($this->createCallback)) {
            $func = $this->createCallback;
            $entity = $func($value);
        }

        return $entity;
    }
}

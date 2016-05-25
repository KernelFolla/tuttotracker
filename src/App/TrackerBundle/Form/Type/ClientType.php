<?php

namespace App\TrackerBundle\Form\Type;

use App\Common\Symfony\Form\Type\AbstractType;
use App\TrackerBundle\Entity\Client;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;


/**
 * @DI\FormType()
 */
class ClientType extends AbstractType
{
    public function __construct()
    {
        $this->setDataClass(Client::class);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
    }
}

<?php

namespace App\TrackerBundle\Form\Type;

use App\Common\Symfony\Form\Type\AbstractType;
use App\TrackerBundle\Entity\Client;
use App\TrackerBundle\Form\Model\ClientFilter;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\FormType
 */
class ClientFilterType extends AbstractType
{
    protected $abstractDefaultOptions = [
        'csrf_protection' => false,
        'area' => 'user'
    ];

    public function __construct()
    {
        $this->setDataClass(ClientFilter::class);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['area'] = 'user';
        $builder
            ->add(
                's',
                null,
                [
                    'attr' => ['placeholder' => 'Search'],
                    'required' => false,
                ]
            );
        if ($options['area'] == 'admin') {
            $builder->add(
                'user',
                'entity',
                [
                    'empty_value' => 'All',
                    'class' => Client::class,
                    'required' => false,
                ]
            );
        }
    }
}

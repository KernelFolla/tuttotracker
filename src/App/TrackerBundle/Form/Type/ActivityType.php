<?php

namespace App\TrackerBundle\Form\Type;

use App\Common\Symfony\Form\Type\AbstractType;
use App\TrackerBundle\Entity\Activity;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;


/**
 * @DI\FormType
 */
class ActivityType extends AbstractType
{
    const TYPE_NAME = 'app_activity';

    public function __construct()
    {
        $this->setDataClass(Activity::class);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('startsAt');
    }
}

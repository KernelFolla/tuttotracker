<?php

namespace App\Common\Symfony\Form\Type;

use Symfony\Component\Form\AbstractType as Base;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Kf\KitBundle\Model\Traits\WithDataClass;

abstract class AbstractType extends Base
{
    use WithDataClass;
    protected $isWithDataClass = true;
    protected $abstractDefaultOptions = [];

    public function configureOptions(OptionsResolver $resolver)
    {
        if($this->isWithDataClass) {
            $resolver
                ->setDefaults(
                    array_merge(
                        $this->abstractDefaultOptions,
                        [
                            'data_class' => $this->getDataClass(),
                        ]
                    )
                );
        } else {
            $resolver->setDefaults($this->abstractDefaultOptions);
        }
    }
}
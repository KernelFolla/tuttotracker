<?php

namespace App\TrackerBundle\Form\Type;

use App\Common\Symfony\Form\DataTransformer\FieldTransformer;
use App\Common\Symfony\Form\Type\AbstractType;
use App\TrackerBundle\Entity\Activity;
use App\TrackerBundle\Entity\Client;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * @DI\FormType
 */
class ActivityType extends AbstractType
{
    const TYPE_NAME = 'app_activity';
    protected $abstractDefaultOptions = ['isNew' => true];

    /** @var ObjectManager */
    private $manager;
    private $security;
    /**
     * @DI\InjectParams({
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "security" = @DI\Inject("security.token_storage"),
     * })
     */
    public function __construct(ObjectManager $entityManager, TokenStorageInterface $security)
    {
        $this->manager = $entityManager;
        $this->security = $security;
        $this->setDataClass(Activity::class);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('client', TextType::class)
            ->add(
                'startsAt',
                TextType::class
            );


        $builder->get('startsAt')->addModelTransformer($this->createDateTransformer());
        $builder->get('client')->addModelTransformer($this->createClientTransformer());
        if (!$options['isNew']) {
            $builder->add(
                'endsAt',
                TextType::class
            );
            $builder->get('endsAt')->addModelTransformer($this->createDateTransformer());
        }
    }

    private function createClientTransformer()
    {


        $transformer = new FieldTransformer('name');
        $repo = $this->manager->getRepository(Client::class);
        $user = $this->security->getToken()->getUser();
        $transformer->setFindCallback(
            function ($value) use ($repo, $user) {
                return $repo->findOneBy(
                    [
                        'createdByUser' => $user->getId(),
                        'name' => $value,
                    ]
                );
            }
        );
        $transformer->setCreateCallback(
            function ($value) {
                $ret = new Client();
                $ret->setName($value);
                $this->manager->persist($ret);
                $this->manager->flush();

                return $ret;
            }
        );

        return $transformer;
    }

    private function createDateTransformer()
    {
        $transformer = new CallbackTransformer(
            function (\DateTime $dateAsObj = null) {
                if ($dateAsObj) {
                    return $dateAsObj->format('U');
                }
            },
            function ($dateAsString = null) {
                if (!empty($dateAsString)) {
                    return new \DateTime($dateAsString);
                } else {
                    return null;
                }
            }
        );

        return $transformer;
    }
}

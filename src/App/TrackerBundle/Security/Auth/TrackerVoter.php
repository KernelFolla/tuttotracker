<?php

namespace App\TrackerBundle\Security\Auth;

use App\TrackerBundle\Entity\Activity;
use App\UserBundle\Entity\User;
use JMS\DiExtraBundle\Annotation as DI;
use Kf\KitBundle\Symfony\Security\AbstractVoter;
use App\TrackerBundle\Entity\Client;
use App\UserBundle\Enum\Role;

/**
 * @DI\Service(public=false)
 * @DI\Tag("security.voter")
 */
class TrackerVoter extends AbstractVoter
{

    public function __construct()
    {
        $this->setDataClass(
            [
                Client::class,
                Activity::class,
            ]
        );
    }


    /**
     * @param  string $attribute
     * @return int
     */
    public function dispatch($attribute)
    {
        /** @var User $user */
        $user = $this->getUser();
        if(!is_object($user)) return $this->checkIf(false);
        $isAdmin = $user->hasRole(Role::ADMIN);
        /** @var Activity|Client $data */
        $data = $this->getEntity();
        $isAuthor = $data->getCreatedByUser()->getId() == $user->getId();
        switch ($attribute) {
            case self::VIEW:
            case self::EDIT:
            case self::DELETE:
                return $this->checkIf($isAdmin || $isAuthor);
        }
    }
}


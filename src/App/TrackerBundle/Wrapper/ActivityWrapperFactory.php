<?php

namespace App\TrackerBundle\Wrapper;

use JMS\DiExtraBundle\Annotation as DI;
use App\TrackerBundle\Entity\Activity;
use Symfony\Component\Security\Core\SecurityContext;
use App\CoreBundle\Wrapper\AbstractWrapperFactory;

/**
 * @DI\Service("app.tracker.activity_wrapper_factory")
 * @DI\Tag("app.wrapper_factory", attributes = {"class" = Activity::class})
 */
class ActivityWrapperFactory extends AbstractWrapperFactory
{
    /**
     * @param Activity $activity
     * @return ActivityWrapper
     */
    public function wrap($activity)
    {
        return new ActivityWrapper(
            [
                'id' => $activity->getId(),
                'name' => $activity->getName(),
                'createdBy' => $activity->getCreatedBy(),
                'createdAt' => $activity->getCreatedAt(),
                'startsAt' => $activity->getStartsAt(),
                'endsAt' => $activity->getEndsAt(),
                'client' => $this->getProvider()->wrap($activity->getClient()),
            ]
        );
    }
}
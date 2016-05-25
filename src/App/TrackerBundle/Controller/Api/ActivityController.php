<?php

namespace App\TrackerBundle\Controller\Api;

use App\Common\Symfony\Action\GenericApiListAction;
use App\Common\Symfony\Controller\RestController;
use App\TrackerBundle\Entity\Activity;
use App\TrackerBundle\Form\Model\ActivityFilter;
use App\TrackerBundle\Form\Type\ActivityFilterType;
use App\TrackerBundle\Form\Type\ActivityType;
use App\UserBundle\Enum\Role;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\RouteResource("activities")
 */
class ActivityController extends RestController
{
    /**
     * Returns JSON representing the current user.
     * @Doc\ApiDoc(
     *   section = "Activity",
     *   output = "App\TrackerBundle\Wrapper\ActivityWrapper",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Not found"
     *   }
     * )
     * @FW\Security("is_granted('view',activity)")
     */
    public function getAction(Activity $activity)
    {
        return View::create($this->wrap($activity));
    }

    /**
     * Returns JSON representing the current user.
     * @Doc\ApiDoc(
     *   section = "Activity",
     *   output = "App\TrackerBundle\Wrapper\ActivityWrapper",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Not found"
     *   }
     * )
     * @FW\Security("is_granted('ROLE_USER')")
     */
    public function cgetAction(Request $request)
    {
        $filter = new ActivityFilter();
        $user = $this->getUser();
        if (!$user->hasRole(Role::ADMIN)) {
            $filter->setUser($this->getUser());
        }

        return GenericApiListAction::create($this)
            ->setRouter($this->get('router'))
            ->setFormFactory($this->get('form.factory'))
            ->setDataClass(Activity::class)
            ->setFilterTypeName(ActivityFilterType::class)
            ->setFilter($filter)
            ->setWrapperCallback(
                function ($content) {
                    return $this->wrap($content);
                }
            )
            ->setRequest($request)
            ->execute();
    }

    /**
     * Creates a new activity
     *
     * @Doc\ApiDoc(
     *  section = "Activity",
     *  input = "App\ActivityBundle\Form\Type\ActivityType",
     *  output = "App\ActivityBundle\Entity\Activity",
     *  statusCodes={
     *         201="Returned when a new Account has been successfully created",
     *         400="Returned when the posted data is invalid"
     *     }
     * )
     *
     * @param Request $request
     * @return View
     * @FW\Security("is_granted('ROLE_USER')")
     */
    public function postAction(Request $request)
    {
        $activity = new Activity();
        $activity->setCreatedByUser($this->getUser());

        return $this->dispatchForm($activity, $request);
    }

    /**
     * Creates a new activity
     *
     * @Doc\ApiDoc(
     *  section = "Activity",
     *  input = "App\ActivityBundle\Form\Type\ActivityType",
     *  output = "App\ActivityBundle\Entity\Activity",
     *  statusCodes={
     *         200="Returned when a new Account has been successfully updated",
     *         400="Returned when the posted data is invalid"
     *     }
     * )
     * @FW\Security("is_granted('edit',activity)")
     */
    public function putAction(Activity $activity, Request $request)
    {
        return $this->dispatchForm($activity, $request);
    }

    /**
     * Ends the activity
     *
     * @Doc\ApiDoc(
     *  section = "Activity",
     *  statusCodes={
     *         200="Returned when a new Account has been successfully updated",
     *         400="Returned when the posted data is invalid"
     *     }
     * )
     * @FW\Security("is_granted('edit',activity)")
     */
    public function patchStopAction(Activity $activity)
    {
        if (!$activity->getEndsAt()) {
            $activity->setEndsAt(new \DateTime());
            $this->save($activity);

            return View::create($this->wrap($activity), Codes::HTTP_OK);
        } else {
            return View::create($this->wrap($activity), Codes::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Deletes an activity
     *
     * @Doc\ApiDoc(
     *  section = "Activity",
     *  statusCodes={
     *         200="Returned when a new Account has been successfully updated",
     *         400="Returned when the posted data is invalid"
     *     }
     * )
     * @FW\Security("is_granted('edit',activity)")
     */
    public function deleteAction(Activity $activity)
    {
        $this->remove($activity);

        return View::create(null, Codes::HTTP_NO_CONTENT);
    }

    private function dispatchForm(Activity $activity, Request $request)
    {
        $isNew = !$activity->getId();
        $form = $this->createForm(
            ActivityType::class,
            $activity,
            [
                'csrf_protection' => false,
                'isNew' => $isNew,
            ]
        );
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $this->save($activity);

            return View::create($this->wrap($activity), $isNew ? Codes::HTTP_CREATED : Codes::HTTP_OK);
        }

        return View::create($form, Codes::HTTP_BAD_REQUEST);
    }
}
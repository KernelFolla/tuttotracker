<?php

namespace App\UserBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\RouteResource("users")
 */
class UserController extends FOSRestController
{
    /**
     * Returns JSON representing the current user.
     * @Doc\ApiDoc(
     *   section = "User",
     *   output = "App\UserBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     403 = "Not found"
     *   }
     * )
     * @FW\Security("is_granted('ROLE_USER')")
     */
    public function getCurrentAction()
    {
        return $this->getUser();
    }

    /**
     * Creates a new Account
     *
     * @Doc\ApiDoc(
     *  input = "FOS\UserBundle\Form\Type\RegistrationType",
     *  output = "App\UserBundle\Entity\User",
     *  statusCodes={
     *         201="Returned when a new Account has been successfully created",
     *         400="Returned when the posted data is invalid"
     *     }
     * )
     *
     * @param Request $request
     * @return View
     */
    public function postAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $jsonData = json_decode($request->getContent(), true); // "true" to get an associative array

        if ('POST' === $request->getMethod()) {
            $form->submit($jsonData);
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                return View::create(null, 201);
            }
        }

        $view = View::create($form, 400);
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}
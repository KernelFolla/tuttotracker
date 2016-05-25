<?php

namespace App\TrackerBundle\Controller\Api;

use App\Common\Symfony\Action\GenericApiListAction;
use App\Common\Symfony\Controller\RestController;
use App\TrackerBundle\Entity\Client;
use App\TrackerBundle\Form\Model\ClientFilter;
use App\TrackerBundle\Form\Type\ClientFilterType;
use App\TrackerBundle\Form\Type\ClientType;
use App\UserBundle\Enum\Role;
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
 * @Rest\RouteResource("clients")
 */
class ClientController extends RestController
{
    /**
     * Returns JSON representing the current user.
     * @Doc\ApiDoc(
     *   section = "Client",
     *   output = "App\TrackerBundle\Wrapper\ClientWrapper",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Not found"
     *   }
     * )
     * @FW\Security("is_granted('view',client)")
     */
    public function getAction(Client $client)
    {
        return View::create($this->wrap($client));
    }

    /**
     * Returns JSON representing the current user.
     * @Doc\ApiDoc(
     *   section = "Client",
     *   output = "App\TrackerBundle\Wrapper\ClientWrapper",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Not found"
     *   }
     * )
     * @FW\Security("is_granted('ROLE_USER')")
     */
    public function cgetAction(Request $request)
    {
        $filter = new ClientFilter();
        $user = $this->getUser();
        if (!$user->hasRole(Role::ADMIN)) {
            $filter->setUser($this->getUser());
        }

        return GenericApiListAction::create($this)
            ->setRouter($this->get('router'))
            ->setFormFactory($this->get('form.factory'))
            ->setDataClass(Client::class)
            ->setFilterTypeName(ClientFilterType::class)
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
     * Creates a new client
     *
     * @Doc\ApiDoc(
     *  section = "Client",
     *  input = "App\ClientBundle\Form\Type\ClientType",
     *  output = "App\ClientBundle\Entity\Client",
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
        $client = new Client();
        $client->setCreatedByUser($this->getUser());

        return $this->dispatchForm($client, $request);
    }

    /**
     * Creates a new client
     *
     * @Doc\ApiDoc(
     *  section = "Client",
     *  input = "App\ClientBundle\Form\Type\ClientType",
     *  output = "App\ClientBundle\Entity\Client",
     *  statusCodes={
     *         200="Returned when a new Account has been successfully updated",
     *         400="Returned when the posted data is invalid"
     *     }
     * )
     * @FW\Security("is_granted('edit',client)")
     */
    public function putAction(Client $client, Request $request)
    {
        return $this->dispatchForm($client, $request);
    }

    /**
     * Deletes a client
     *
     * @Doc\ApiDoc(
     *  section = "Client",
     *  statusCodes={
     *         200="Returned when a new Account has been successfully updated",
     *         400="Returned when the posted data is invalid"
     *     }
     * )
     * @FW\Security("is_granted('edit',client)")
     */
    public function deleteAction(Client $client)
    {
        $this->remove($client);

        return View::create(null, Codes::HTTP_NO_CONTENT);
    }

    private function dispatchForm(Client $client, Request $request)
    {
        $isNew = !$client->getId();
        $form = $this->createForm(ClientType::class, $client, ['csrf_protection' => false]);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $this->save($client);

            return View::create($this->wrap($client), $isNew ? Codes::HTTP_CREATED : Codes::HTTP_OK);
        }

        return View::create($form, Codes::HTTP_BAD_REQUEST);
    }
}
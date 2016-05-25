<?php

namespace App\TrackerBundle\Controller\Api;

use App\Common\Symfony\Controller\RestController;
use App\TrackerBundle\Entity\Client;
use App\TrackerBundle\Form\Type\ClientType;
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
 * @FW\Security("is_granted('ROLE_USER')")
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
     * Creates a new client
     *
     * @Doc\ApiDoc(
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
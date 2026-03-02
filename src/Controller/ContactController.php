<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Model\ContactRequestData;
use App\Form\Type\ContactRequestType;
use App\Service\Contact\ContactEmailSenderInterface;
use App\Service\Contact\ContactRecipientResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        ContactRecipientResolver $recipientResolver,
        ContactEmailSenderInterface $contactEmailSender,
    ): Response {
        $contactRequest = new ContactRequestData();
        $form = $this->createForm(ContactRequestType::class, $contactRequest);
        $form->handleRequest($request);

        $showValidationErrorMessage = false;
        $showSuccessState = $request->query->getBoolean('sent', false);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $contactEmailSender->send(
                        $recipientResolver->resolve(),
                        $contactRequest->toPayload(),
                    );

                    return $this->redirectToRoute('app_contact', ['sent' => 1]);
                } catch (\Throwable) {
                    $this->addFlash('error', 'Impossible d\'envoyer votre demande. Merci de réessayer ou de nous appeler directement.');
                }
            } else {
                $showValidationErrorMessage = true;
            }
        }

        return $this->render('pages/contact.html.twig', [
            'contactForm' => $form->createView(),
            'showValidationErrorMessage' => $showValidationErrorMessage,
            'showSuccessState' => $showSuccessState,
        ]);
    }
}

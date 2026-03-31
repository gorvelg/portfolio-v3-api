<?php

namespace App\Controller\Api;

use App\Dto\ContactRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends AbstractController
{
    #[Route('/api/contact', name: 'api_contact', methods: ['POST'])]
    public function __invoke(
        Request $request,
        ValidatorInterface $validator,
        MailerInterface $mailer
    ): JsonResponse {
        $payload = json_decode($request->getContent(), true);

        if (!is_array($payload)) {
            return $this->json([
                'message' => 'Payload JSON invalide',
            ], 400);
        }

        $contactRequest = new ContactRequest();
        $contactRequest->name = trim($payload['name'] ?? '');
        $contactRequest->firstname = trim($payload['firstname'] ?? '');
        $contactRequest->phone = trim($payload['phone'] ?? '');
        $contactRequest->email = trim($payload['email'] ?? '');
        $contactRequest->message = trim($payload['message'] ?? '');
        $contactRequest->website = trim($payload['website'] ?? '');

        if (!empty($contactRequest->website)) {
            return $this->json([
                'message' => 'Requête invalide.',
            ], 400);
        }

        $errors = $validator->validate($contactRequest);

        if (count($errors) > 0) {
            $formattedErrors = [];

            foreach ($errors as $error) {
                $formattedErrors[] = [
                    'field' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                ];
            }

            return $this->json([
                'message' => 'Le formulaire contient des erreurs.',
                'errors' => $formattedErrors,
            ], 422);
        }

        $email = (new TemplatedEmail())
            ->from('contact@guillaume-gorvel.fr')
            ->to('contact@guillaume-gorvel.fr')
            ->replyTo($contactRequest->email)
            ->subject('Nouveau message depuis le portfolio.')
            ->htmlTemplate('emails/contact.html.twig')
            ->context([
                'contactRequest' => $contactRequest,
            ])
        ;

        $mailer->send($email);

        return $this->json([
            'message' => 'Message envoyé avec succès.'
        ], 200);
    }
}

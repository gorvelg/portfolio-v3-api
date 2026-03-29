<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ContactRequest {

    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne doit pas dépasser {{ limit }} caractères.'
    )]
    public ?string $name = null;
    #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le prénom ne doit pas dépasser {{ limit }} caractères.'
    )]
    public ?string $firstname = null;

    #[Assert\NotBlank(message: 'L’email est obligatoire.')]
    #[Assert\Email(message: 'L’adresse email n’est pas valide.')]
    public ?string $email = null;

    public ?string $phone = null;

    #[Assert\NotBlank(message: 'Le champs message est obligatoire')]
    public ?string $message = null;

    public ?string $website = null;

}

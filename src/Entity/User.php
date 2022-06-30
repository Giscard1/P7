<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * @Hateoas\Relation(
 *      "Retourne les information d’un utilisateur",
 *      href = @Hateoas\Route(
 *          "OneClient",
 *          parameters = { "id" = "expr(object.getCustomer().getId())", "userId" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="userDetail")
 * )
 *
 * * @Hateoas\Relation(
 *      "Créer un utilisateur",
 *      href = @Hateoas\Route(
 *          "app_api_create_user",
 *     parameters = { "id" = "expr(object.getCustomer().getId())" }
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups="Créer un utilisateur")
 * )
 *
 * @Hateoas\Relation(
 *      "Supprimer un utilisateur",
 *      href = @Hateoas\Route(
 *          "app_api_delete_user",
 *          parameters = { "id" = "expr(object.getCustomer().getId())", "idUser" = "expr(object.getId())" }
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups="Supprimer un utilisateur")
 * )
 *
 * @Hateoas\Relation(
 *      "Liste des clients",
 *      href = @Hateoas\Route(
 *          "allClientsOfACustomer",
 *     parameters = { "id" = "expr(object.getId())" }
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups="Liste des clients")
 * )
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Serializer\Groups(['userDetail'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Serializer\Groups(['userDetail','Liste des clients'])]
    /**
     * @Assert\Length(
     *      min=2, max=70,
     *      minMessage="Le prénom doit comporter 2 caractères minimum",
     *      maxMessage="Le prénom doit comporter 15 caractères maximum"
     * )
     * @Assert\NotBlank(message = "Le prénom ne peut être vide.")
     */
    private $first_name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Serializer\Groups(['userDetail', 'Liste des clients'])]
    /**
     * @Assert\Length(
     *      min=2, max=70,
     *      minMessage="Le nom doit comporter 2 caractères minimum",
     *      maxMessage="Le nom doit comporter 15 caractères maximum"
     * )
     * @Assert\NotBlank(message = "Le nom ne peut être vide.")
     */
    private $last_name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Serializer\Groups(['userDetail', 'Liste des clients'])]
    /**
     * @Assert\NotBlank(message = "Le champ mail ne peut être vide.")
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas un mail valide."
     * )
     */
    private $email;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'user')]
    #[ORM\JoinColumn(nullable: false)]
    /**
     * @Assert\NotBlank(message = "Le champ customer ne peut être vide.")
     */
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}

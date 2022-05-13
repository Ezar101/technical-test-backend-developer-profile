<?php

declare(strict_types=1);

namespace App\Entity;

use App\OpenApi\OpenApiFactory;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\ProfileController;
use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Table(name: '`users`')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[
    ApiResource(
        security: 'is_granted("ROLE_SUPER_ADMIN")',
        collectionOperations: [
            'profile' => [
                'pagination_enabled' => false,
                'path' => ProfileController::PROFILE_PATH,
                'method' => 'get',
                'controller' => ProfileController::class,
                'read' => false,
                // 'security' => 'is_granted("ROLE_SUPER_ADMIN")',
                'openapi_context' => [
                    'security' => [
                        'apiKey' => ['cookieAuth' => []]
                    ]
                ]
            ]
        ],
        itemOperations: [
            'get' => [
                'controller' => NotFoundAction::class,
                'openapi_context' => [
                    'summary' => OpenApiFactory::OPEN_API_CONTEXT_HIDDEN
                ],
                'read' => false,
                'output' => false
            ]
        ],
        normalizationContext: [
            'groups' => ['read:User']
        ]
    )
]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['read:User'])]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[Groups(['read:User'])]
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}

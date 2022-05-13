<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MemberRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: '`members`')]
#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ApiResource()]
class Member extends AbstractEntity
{
    #[
        ORM\Column(type: 'string', length: 255),
        Assert\NotBlank()
    ]
    private string $firstname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $lastname;

    #[
        ORM\ManyToOne(targetEntity: MemberGroup::class, inversedBy: 'members', cascade: ['persist']),
        Assert\Valid()
    ]
    private ?MemberGroup $memberGroup;

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getMemberGroup(): ?MemberGroup
    {
        return $this->memberGroup;
    }

    public function setMemberGroup(?MemberGroup $memberGroup): self
    {
        $this->memberGroup = $memberGroup;

        return $this;
    }
}

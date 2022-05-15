<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MemberRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name: '`members`')]
#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ApiResource()]
class Member extends AbstractEntity
{
    #[ORM\Column(type: 'string', length: 255)]
    private string $firstname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $lastname;

    /**
     * @var MemberGroup[]|Collection
     */
    #[ORM\ManyToMany(targetEntity: MemberGroup::class, mappedBy: 'members', cascade: ['all'])]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $memberGroups;

    public function __construct()
    {
        $this->memberGroups = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getFirstname() . " " . $this->getLastname();
    }

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

    /**
     * @return Collection<int, MemberGroup>
     */
    public function getMemberGroups(): Collection
    {
        return $this->memberGroups;
    }

    public function addMemberGroup(MemberGroup $memberGroup): self
    {
        if (!$this->getMemberGroups()->contains($memberGroup)) {
            $this->memberGroups[] = $memberGroup;
            $memberGroup->addMember($this);
        }

        return $this;
    }

    public function removeMemberGroup(MemberGroup $memberGroup): self
    {
        if ($this->getMemberGroups()->removeElement($memberGroup)) {
            $memberGroup->removeMember($this);
        }

        return $this;
    }
}

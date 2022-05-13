<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MemberGroupRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: '`member_groups`')]
#[ORM\Entity(repositoryClass: MemberGroupRepository::class)]
#[ApiResource()]
class MemberGroup extends AbstractEntity
{
    #[
        ORM\Column(type: 'string', length: 255, unique: true),
        Assert\NotBlank()
    ]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\OneToMany(mappedBy: 'memberGroup', targetEntity: Member::class)]
    private $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setMemberGroup($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getMemberGroup() === $this) {
                $member->setMemberGroup(null);
            }
        }

        return $this;
    }
}

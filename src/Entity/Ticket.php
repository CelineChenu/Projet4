<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dayTicket;

    /**
     * @ORM\Column(type="boolean")
     */
    private $discountTicket;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $country;

    /**
     * @ORM\Column(type="integer")
     */
    private $commandId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Command", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $command;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayTicket(): ?bool
    {
        return $this->dayTicket;
    }

    public function setDayTicket(bool $dayTicket): self
    {
        $this->dayTicket = $dayTicket;

        return $this;
    }

    public function getDiscountTicket(): ?bool
    {
        return $this->discountTicket;
    }

    public function setDiscountTicket(bool $discountTicket): self
    {
        $this->discountTicket = $discountTicket;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate = null): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCommandId(): ?int
    {
        return $this->commandId;
    }

    public function setCommandId(int $commandId): self
    {
        $this->commandId = $commandId;

        return $this;
    }

    public function getCommand(): ?Command
    {
        return $this->command;
    }

    public function setCommand(?Command $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}

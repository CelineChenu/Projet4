<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Validator\Constrains as MyValidator;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @MyValidator\VisitNumber()
 * @ORM\Entity(repositoryClass="App\Repository\CommandRepository")
 */
class Command
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     * @MyValidator\ClosedDay
     */
    private $visitDay;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="command", orphanRemoval=true, cascade={"persist","remove"}, fetch="EAGER")
     */
    private $tickets;

    /**
     * @ORM\Column(type="integer")
     */
    private $ticketNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisitDay(): ?\DateTimeInterface
    {
        return $this->visitDay;
    }

    public function setVisitDay(\DateTimeInterface $visitDay): self
    {
        $this->visitDay = $visitDay;

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

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection|ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setCommand($this);
        }

        return $this;
    }

    public function removeTicket(ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getCommand() === $this) {
                $ticket->setCommand(null);
            }
        }

        return $this;
    }

    public function getTicketNumber(): ?int
    {
        return $this->ticketNumber;
    }

    public function setTicketNumber(int $ticketNumber): self
    {
        $this->ticketNumber = $ticketNumber;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}

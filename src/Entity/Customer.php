<?php

namespace App\Entity;


use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;



/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @ApiResource(
 *       
 *       normalizationContext={"groups"="customer"},
 *       denormalizationContext={"groups"="createCustomer","updateCustomer"},
 *      
 *       collectionOperations={
 *                              "GET", 
 *                              "POST"={
 *                                      "security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *                                      "controller"=App\Controller\Api\CustomerCreateController::class ,
 *                                      },
 *                             },
 *      itemOperations={
 *                      "GET",
 *                      "PUT"={
 *                             "security"="is_granted('EDIT_CUSTOMER', object)",
 *                             "controller"=App\Controller\Api\CustomerCreateController::class,
 *                            },
 *                      "DELETE"={
 *                             "security"="is_granted('EDIT_CUSTOMER', object)",
 *                             "controller"=App\Controller\Api\CustomerCreateController::class
 *                            },
 *                      },
 *                 
 * )
 * @ApiFilter( SearchFilter::class , properties={"user": "exact"})
 */



class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"customer"})
     */

    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customers")
     * @Groups({"customer"})
     */
    
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer","createCustomer","updateCustomer"})
     */
    
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer"})
     */
    
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer"})
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"customer"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer"})
     */
    private $society;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer"})
     */
    
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer"})
     */
    private $city;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"customer"})
     */
    
    private $fidelityPoint;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer"})
     */
    private $service;

    public function __construct()
    {
        $this->fidelityPoint = 0 ;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(?int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSociety(): ?string
    {
        return $this->society;
    }

    public function setSociety(?string $society): self
    {
        $this->society = $society;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getFidelityPoint(): ?int
    {
        return $this->fidelityPoint;
    }

    public function setFidelityPoint(?int $fidelityPoint): self
    {
        $this->fidelityPoint = $fidelityPoint;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): self
    {
        $this->service = $service;

        return $this;
    }

    public  function __toString()
    {
        return $this->getEmail();
    }

    
}

<?php

namespace App\Entity;

use App\Repository\GiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: GiteRepository::class)]
class Gite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[ORM\Column]
    private ?string $city = null;

    #[ORM\Column]
    private ?string $department = null;

    #[ORM\Column]
    private ?string $region = null;

    #[ORM\Column]
    private ?float $surface = null;

    /**
     * @var UploadedFile|null
     * @Assert\NotBlank(message: "Please upload the photo.")
     * @Assert\Image(
     *     maxSize = "2048k",
     *     mimeTypes = {"image/jpeg", "image/png", "image/gif", "image/webp"},
     *     mimeTypesMessage = "Please upload a valid image",
     *     maxSizeMessage = "The maxmimum allowed file size is 1MB."
     * )
     * @ORM\Transient
     */
    private $photoFile;
    public function getPhotoFile(): ?UploadedFile
    {
        return $this->photoFile;
    }

    public function setPhotoFile(?UploadedFile $photoFile): self
    {
        $this->photoFile = $photoFile;

        return $this;
    }
    #[ORM\Column]
    private ?string $photos = null;

    #[ORM\Column]
    private ?int $rooms = null;

    #[ORM\Column]
    private ?int $beds = null;

    #[ORM\Column]
    private ?float $base_price = null;

    #[ORM\Column]
    private ?int $id_price_period = null;

    #[ORM\Column]
    private ?bool $pets = null;

    #[ORM\Column(nullable: true)]
    private ?float $pets_price = null;

    #[ORM\ManyToOne(inversedBy: 'gites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Services::class, mappedBy: 'service')]
    private Collection $services;

    public function __construct()
    {
        $this->services = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(float $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getPhotos(): ?string
    {
        return $this->photos;
    }

    public function setPhotos(string $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getBeds(): ?int
    {
        return $this->beds;
    }

    public function setBeds(int $beds): self
    {
        $this->beds = $beds;

        return $this;
    }

    public function getBasePrice(): ?float
    {
        return $this->base_price;
    }

    public function setBasePrice(float $base_price): self
    {
        $this->base_price = $base_price;

        return $this;
    }

    public function getIdPricePeriod(): ?int
    {
        return $this->id_price_period;
    }

    public function setIdPricePeriod(int $id_price_period): self
    {
        $this->id_price_period = $id_price_period;

        return $this;
    }

    public function getPets(): ?bool
    {
        return $this->pets;
    }

    public function setPets(bool $pets): self
    {
        $this->pets = $pets;

        return $this;
    }

    public function getPetsPrice(): ?float
    {
        return $this->pets_price;
    }

    public function setPetsPrice(?float $pets_price): self
    {
        $this->pets_price = $pets_price;

        return $this;
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

    /**
     * @return Collection<int, Services>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Services $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->addService($this);
        }

        return $this;
    }

    public function removeService(Services $service): self
    {
        if ($this->services->removeElement($service)) {
            $service->removeService($this);
        }

        return $this;
    }

}

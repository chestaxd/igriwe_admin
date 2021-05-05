<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\GetRelevantGames;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={
 *          "get"={
 *              "path"="/game/{id}"
 *           },
 *           "get_relative_games"={
 *              "method"="GET",
 *              "path"="/game/{id}/relevant",
 *              "controller"=GetRelevantGames::class,
 *              "normalization_context"={"groups"={"game:relevand"}},
 *           }
 *     },
 *     normalizationContext={"groups"={"game:read"}},
 * )
 * @ApiFilter(SearchFilter::class,properties={
 *     "name": "partial",
 *     "id": "exact",
 * })
 * @ApiFilter(DateFilter::class,properties={"createDate"})
 * @ApiFilter(BooleanFilter::class, properties={"isPublished"})
 * @ApiFilter(PropertyFilter::class)
 */
class Game
{

    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"game:read","category:read","game:relevand"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("game:read","category:read","game:relevand")
     */
    private $name;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("game:read")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", options={"default": 1})
     * @Groups("game:read")
     */
    private $ads = 1;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("game:read")
     */
    private $data;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("game:read")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="games")
     * @ORM\JoinTable(name="game_tag")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("game:read","category:read","game:relevand")
     */
    private $img;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("game:read","category:read")
     */
    private $isPublished = 0;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("game:read")
     */
    private $keywords;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAds(): ?bool
    {
        return $this->ads;
    }

    public function setAds(bool $ads): self
    {
        $this->ads = $ads;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }


    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return int
     */
    public function getIsPublished(): int
    {
        return $this->isPublished;
    }

    /**
     * @param int $isPublished
     */
    public function setIsPublished(int $isPublished): void
    {
        $this->isPublished = $isPublished;
    }
}

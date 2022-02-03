<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Books
 *
 * @ORM\Table(name="books", indexes={@ORM\Index(name="last_user", columns={"last_user"})})
 * @ORM\Entity
 */
class Books
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="text", length=65535, nullable=false)
     */
    private $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=3000, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="publisher", type="string", length=255, nullable=false)
     */
    private $publisher;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="text", length=65535, nullable=false)
     */
    private $category;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="loan_date", type="datetime", nullable=true)
     */
    private $loanDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="due_date", type="datetime", nullable=true)
     */
    private $dueDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image_name", type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image_file", type="blob", length=0, nullable=true)
     */
    private $imageFile;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="last_user", referencedColumnName="id")
     * })
     */
    private $lastUser;

    /**
     * @ORM\OneToMany(targetEntity=History::class, mappedBy="book")
     */
    private $book_history;

    public function __construct()
    {
        $this->book_history = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function setPublisher(string $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getLoanDate(): ?\DateTimeInterface
    {
        return $this->loanDate;
    }

    public function setLoanDate(?\DateTimeInterface $loanDate): self
    {
        $this->loanDate = $loanDate;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    public function getLastUser(): ?User
    {
        return $this->lastUser;
    }

    public function setLastUser(?User $lastUser): self
    {
        $this->lastUser = $lastUser;

        return $this;
    }

    /**
     * @return Collection|History[]
     */
    public function getBookHistory(): Collection
    {
        return $this->book_history;
    }

    public function addBookHistory(History $bookHistory): self
    {
        if (!$this->book_history->contains($bookHistory)) {
            $this->book_history[] = $bookHistory;
            $bookHistory->setBook($this);
        }

        return $this;
    }

    public function removeBookHistory(History $bookHistory): self
    {
        if ($this->book_history->removeElement($bookHistory)) {
            // set the owning side to null (unless already changed)
            if ($bookHistory->getBook() === $this) {
                $bookHistory->setBook(null);
            }
        }

        return $this;
    }


}

<?php

namespace Sean\Book\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

/**
* @ORM\Entity
* @ORM\Table(name="assignment_book")
* @Hateoas\Relation("self", href = "expr('/api/v1/books/' ~ object.getId())")
*/
class Book
{
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=13, nullable=true)
     * @Assert\Length(
     *      min = 13,
     *      max = 13,
     *      minMessage = "ISBN must be at least {{ limit }} character long",
     *      maxMessage = "ISBN must be at most {{ limit }} characters long"
     * )
     * @Assert\Type(type="string", message="ISBN {{ value }} is not valid.")
     */
    protected $isbn;
    
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "Title must be at least {{ limit }} character long",
     *      maxMessage = "Title must be at most {{ limit }} characters long"
     * )
     * @Assert\Type(type="string", message="Title {{ value }} is not a valid {{ type }}.")
     */
    protected $title;
    
    /**
     * @ORM\Column(type="string", length=800)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 800,
     *      minMessage = "Synopsis must be at least {{ limit }} character long",
     *      maxMessage = "Synopsis must be at most {{ limit }} characters long"
     * )
     * @Assert\Type(type="string", message="Synopsis {{ value }} is not a valid {{ type }}.")
     */
    protected $synopsis;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "Author must be at least {{ limit }} character long",
     *      maxMessage = "Author must be at most {{ limit }} characters long"
     * )
     * @Assert\Type(type="string", message="Author {{ value }} is not a valid {{ type }}.")
     */
    protected $author;
    
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\Type(type="object")
     */
    protected $dateAdded;
    
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "Genre must be at least {{ limit }} character long",
     *      maxMessage = "Genre must be at most {{ limit }} characters long"
     * )
     * @Assert\Type(type="string", message="Genre {{ value }} is not a valid {{ type }}.")
     */
    protected $genre;
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }
    
    public function getIsbn()
    {
        return $this->isbn;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;
    }
    
    public function getSynopsis()
    {
        return $this->synopsis;
    }
    
    public function setAuthor($author)
    {
        $this->author = $author;
    }
    
    public function getAuthor()
    {
        return $this->author;
    }
    
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }
    
    public function getDateAdded()
    {
        return $this->dateAdded;
    }
    
    public function setGenre($genre)
    {
        $this->genre = $genre;
    }
    
    public function getGenre()
    {
        return $this->genre;
    }
}
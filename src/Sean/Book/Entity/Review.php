<?php

namespace Sean\Book\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Hateoas\Configuration\Annotation as Hateoas;

/**
* @ORM\Entity
* @ORM\Table(name="assignment_review")
* @ExclusionPolicy("all")
* @Hateoas\Relation("self", href = "expr('/api/v1/books/' ~ object.getBook().getId() ~ '/reviews/' ~ object.getId())")
*/
class Review
{
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @Expose
    */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Book")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     */
    protected $book;
    
    /**
     * @ORM\Column(type="string", length=800)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 800,
     *      minMessage = "Review must be at least {{ limit }} character long",
     *      maxMessage = "Review must be at most {{ limit }} characters long"
     * )
     * @Assert\Type(type="string", message="Review {{ value }} is not a valid {{ type }}.")
     * @Expose
     */
    protected $review;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="You must give a rating")
     * @Assert\Range(
     *      min = 1,
     *      max = 5,
     *      minMessage = "Rating must be at least {{ limit }} star",
     *      maxMessage = "Rating cannot be greater than {{ limit }} stars"
     * )
     * @Assert\Type(type="integer", message="Rating {{ value }} is not a valid {{ type }}.")
     * @Expose
     */
    protected $rating;
    
    /**
     * @ORM\ManyToOne(targetEntity="Users")
     * @Expose
     */
    protected $reviewer;
    
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\Type(type="object")
     * @Expose
     */
    protected $dateAdded;
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setBook($book)
    {
        $this->book = $book;
    }
    
    public function getBook()
    {
        return $this->book;
    }
    
    public function setReview($review)
    {
        $this->review = $review;
    }
    
    public function getReview()
    {
        return $this->review;
    }
    
    public function setRating($rating)
    {
        $this->rating = (int)$rating;
    }
    
    public function getRating()
    {
        return $this->rating;
    }
    
    public function setReviewer($reviewer)
    {
        $this->reviewer = $reviewer;
    }
    
    public function getReviewer()
    {
        return $this->reviewer;
    }
    
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }
    
    public function getDateAdded()
    {
        return $this->dateAdded;
    }
}
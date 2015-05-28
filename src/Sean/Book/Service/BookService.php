<?php

namespace Sean\Book\Service;

use DateTime;
use Doctrine\ORM\EntityManager;
use Sean\Book\Entity\Book;

class BookService
{
    private $entityManager;

    function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    function getNewBook()
    {
        $newBook = new Book();
        $newBook->setDateAdded(new DateTime());
        return $newBook;
    }
    
    function getBooks()
    {
        $books = $this->entityManager->getRepository('Sean\Book\Entity\Book');
        $books = $books->createQueryBuilder('book')
            ->orderBy('book.dateAdded', 'DESC')
            ->getQuery()->getResult();
        return $books;
    }
    
    function getBookById($id)
    {
        return $this->entityManager->getRepository('Sean\Book\Entity\Book')->find($id);
    }
    
    function getBooksByTitle($title)
    {
        $books = $this->entityManager->getRepository('Sean\Book\Entity\Book');
        $books_query = $books->createQueryBuilder('book')
            ->where('book.title like :title')
            ->orderBy('book.dateAdded', 'DESC')
            ->setParameter('title', '%'.$title.'%')
            ->getQuery();
        return $books_query->getResult();
    }
    
    function storeBook(Book $book)
    {
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }
    
    function deleteBookById($id)
    {
        $this->entityManager->getRepository('Sean\Book\Entity\Book')
            ->createQueryBuilder('book')->delete("Sean\Book\Entity\Book", "book")
            ->where("book.id = :id")
            ->setParameter('id', $id)
            ->getQuery()->execute();
    }
    
    function updateBook(Book $book)
    {
        $this->entityManager->flush($book);
    }
}
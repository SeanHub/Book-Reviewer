<?php

namespace Sean\ApiBundle\Controller;
    
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

use Sean\Book\Forms\Book\BookType;

class BookController extends FOSRestController
{
    public function deleteBookAction($id)
    {
        $book_service = $this->get("book_service");
        $book_service->deleteBookById($id);
        return $this->handleView($this->view(), 204);
    }
    
    public function getBooksAction()
    {
        $book_service = $this->get("book_service");
        $books = $book_service->getBooks();
        if($books)
        {
            return $this->handleView($this->view($books), 200);
        }
        else
        {
            return $this->handleView($this->view([]), 200);
        }
    }
    
    public function getBookAction($id)
    {
        $book_service = $this->get("book_service");
        $book = $book_service->getBookById($id);
        if($book)
        {
            return $this->handleView($this->view($book), 200);
        }
        else
        {
            throw $this->createNotFoundException('book does not exist');
        }
    }
    
    public function postBooksAction(Request $request)
    {
        $bookService = $this->get("book_service");
        $newBook = $bookService->getNewBook();
        $form = $this->createForm(new BookType(), $newBook, array('csrf_protection' => false));
        $form->bind($request);
        
        if ($form->isValid())
        {
            $newBookData = $form->getData();
            $bookService->storeBook($newBookData);
            return $this->handleView($this->view($newBookData), 201);
        }
        
        return $this->handleView($this->view($form), 400);
    }
    
    public function putBookAction($id, Request $request)
    {
        $bookService = $this->get("book_service");
        $oldBook = $bookService->getBookById($id);
        if($oldBook)
        {
            $form = $this->createForm(new BookType(), $oldBook, array('csrf_protection' => false));
            $form->bind($request);

            if ($form->isValid())
            {
                $newBookData = $form->getData();
                $bookService->updateBook($newBookData);
                return $this->handleView($this->view($newBookData), 200);
            }
            
            return $this->handleView($this->view($form), 400);
        }
        else
        {
            throw $this->createNotFoundException('book does not exist');
        }
    }
}
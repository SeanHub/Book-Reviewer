<?php

namespace Sean\Book\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Sean\Book\Forms\Book\BookType;
use Sean\Book\Forms\Book\SearchType;

use GuzzleHttp\Client;

class BookController extends Controller
{
    /**
     * Index Controller
     * @param  object Request $request HTTP Request
     * @return object Symfony Response
     */
    public function indexAction(Request $request)
    {
        $book_service = $this->get("book_service");
        
        $searchedBook = $book_service->getNewBook();
        $searchedBook->setSynopsis('placeholder');
        $searchedBook->setAuthor('placeholder');
        $searchedBook->setGenre('placeholder');
        $form = $this->createForm(new SearchType(), $searchedBook);
        $form->handleRequest($request);
        $client = new Client();
        $bestSellers = null;
        $externalSearchResults = null;
        
        if ($form->isValid())
        {
            $searchedBook = $form->getData();
            $books = $book_service->getBooksByTitle($searchedBook->getTitle());
            
            //Search Google Books
            $response = $client->get('https://www.googleapis.com/books/v1/volumes?q='.$searchedBook->getTitle());
            $responseJSON = $response->json();
            if ($responseJSON['totalItems'] !== 0)
            {
                $externalSearchResults = $responseJSON['items'];
            };
        }
        else
        {
            $books = $book_service->getBooks();
        }
        
        //Get Best Sellers
        $client = new Client();
        $response = $client->get('http://api.nytimes.com/svc/books/v3/lists?api-key=1ae32974396168563951599f16a59892:18:72000642&list=hardcover-fiction');
        $responseJSON = $response->json();
        if ($responseJSON['status'] == 'OK')
        {
            $bestSellers = $responseJSON['results'];
        };
        
        return $this->render('BookBundle:Book:index.html.twig', array('books' => $books, 'form' => $form->createView(), 'bestSellers' => $bestSellers, 'searched' => $form->isValid(), 'externalSearchResults' => $externalSearchResults));
    }
    
    /**
     * Add Book Controller
     * @param  object Request $request HTTP Request
     * @return object Symfony Response
     */
    public function addAction(Request $request)
    {   
        $bookService = $this->get("book_service");
        
        $newBook = $bookService->getNewBook();
        $isbn = $request->get('isbn');
        if($isbn)
        {
            $newBook->setIsbn($isbn);
        }
        $form = $this->createForm(new BookType(), $newBook);
        $form->handleRequest($request);
        
        if ($form->isValid())
        {
            $newBookData = $form->getData();
            $bookService->storeBook($newBookData);
            return new RedirectResponse($this->generateUrl('book_view', array('id' => $newBookData->getId())));
        }
        
        return $this->render('BookBundle:Book:add.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * View Book Controller
     * @param  object Request $request HTTP Request
     * @return object Symfony Response
     */
    public function viewAction(Request $request, $id)
    {
        $book_service = $this->get("book_service");
        $review_service = $this->get("review_service");
        
        $book = $book_service->getBookById($id);
        $review_query = $review_service->getReviewsByBookId($id);
        $averageRating = $review_service->getAverageRating($review_query);
        
        $hasReviewed = false;
        if($this->getUser())
        {
            $hasReviewed = (bool)$review_service->getReviewsByUserAndBookId($this->getUser()->getId(), $id)->getResult();
        }
        
        return $this->render('BookBundle:Book:view.html.twig', array('book' => $book, 'reviews' => $review_query, 'hasReviewed' => $hasReviewed, 'averageRating' => $averageRating));
    }
    
    /**
     * View External Book Controller
     * @param  object Request $request HTTP Request
     * @return object Symfony Response
     */
    public function viewExternalAction(Request $request, $id)
    {
        $client = new Client();
        $response = $client->get('https://www.googleapis.com/books/v1/volumes/'.$id, ['exceptions' => false]);
        $responseJSON = $response->json();
        $statusCode = $response->getStatusCode();
        
        if($statusCode !== 200)
        {
            return new RedirectResponse($this->generateUrl('main'));
        }
        
        $isbn = isset($responseJSON['volumeInfo']['industryIdentifiers']) ? $responseJSON['volumeInfo']['industryIdentifiers'] : null;
        $book = array('id' => $responseJSON['id'], 'title' => isset($responseJSON['volumeInfo']['title']) ? $responseJSON['volumeInfo']['title'] : null, 'author' => isset($responseJSON['volumeInfo']['authors'][0]) ? $responseJSON['volumeInfo']['authors'][0] : null, 'isbn' => $isbn, 'dateAdded' => isset($responseJSON['volumeInfo']['publishedDate']) ? $responseJSON['volumeInfo']['publishedDate'] : null, 'synopsis' => isset($responseJSON['volumeInfo']['description']) ? $responseJSON['volumeInfo']['description'] : null);
        $averageRating = isset($responseJSON['volumeInfo']['averageRating']) ? $responseJSON['volumeInfo']['averageRating'] : 0;
        
        return $this->render('BookBundle:Book:viewexternal.html.twig', array('book' => $book, 'averageRating' => $averageRating));
    }
}
<?php

namespace Sean\Book\Service;

use DateTime;
use Doctrine\ORM\EntityManager;
use Sean\Book\Entity\Review;

class ReviewService
{
    private $entityManager;

    function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    function getNewReview()
    {
        $newReview = new Review();
        $newReview->setDateAdded(new DateTime());
        return $newReview;
    }
    
    function getReviewByReviewAndBookId($id, $bookId)
    {
        $reviews = $this->entityManager->getRepository('Sean\Book\Entity\Review');
        $review_query = $reviews->createQueryBuilder('review')
            ->where('review.id = :id')
            ->andWhere('review.book = :bookId')
            ->orderBy('review.dateAdded', 'DESC')
            ->setParameters(array('id' => $id, 'bookId' => $bookId))
            ->getQuery();
        return $review_query->getResult();
    }
    
    function getReviewById($id)
    {
        $reviews = $this->entityManager->getRepository('Sean\Book\Entity\Review');
        $review_query = $reviews->createQueryBuilder('review')
            ->where('review.id = :id')
            ->setParameter('id', $id)
            ->getQuery();
        return $review_query->getResult();
    }
    
    function getReviewsByBookId($id)
    {
        $reviews = $this->entityManager->getRepository('Sean\Book\Entity\Review');
        $review_query = $reviews->createQueryBuilder('review')
            ->where('review.book = :id')
            ->orderBy('review.dateAdded', 'DESC')
            ->setParameter('id', $id)
            ->getQuery();
        return $review_query->getResult();
    }
    
    function getReviewsByUserAndBookId($userId, $bookId)
    {
        $user_reviews = $this->entityManager->getRepository('Sean\Book\Entity\Review');
        $user_reviews_query = $user_reviews->createQueryBuilder('review')
            ->where('review.book = :id')
            ->andWhere('review.reviewer = :reviewerId')
            ->setParameters(array('id' => $bookId, 'reviewerId' => $userId))
            ->getQuery();
        return $user_reviews_query;
    }
    
    function getAverageRating($reviews)
    {
        $averageRating = 0;
        $reviewCount = count($reviews);
        if($reviewCount > 0)
        {
            for($i = 0; $i < $reviewCount; $i++)
            {
                $averageRating += $reviews[$i]->getRating();
            };
            
            return $averageRating / $reviewCount;
        }
        return $averageRating;
    }
    
    function storeReview(Review $review)
    {
        $this->entityManager->persist($review);
        $this->entityManager->flush();
    }
    
    function updateReview(Review $review)
    {
        $this->entityManager->flush($review);
    }
    
    function deleteReviewByUserAndBookId($userId, $bookId)
    {
        $this->entityManager->getRepository('Sean\Book\Entity\Review')
            ->createQueryBuilder('review')->delete("Sean\Book\Entity\Review", "review")
            ->where("review.book = :id")
            ->andWhere("review.reviewer = :reviewerId")
            ->setParameters(array(':id' => $bookId, ':reviewerId' => $userId))
            ->getQuery()->execute();
    }
    
    function deleteReviewById($id)
    {
        $this->entityManager->getRepository('Sean\Book\Entity\Review')
            ->createQueryBuilder('review')->delete("Sean\Book\Entity\Review", "review")
            ->where("review.id = :id")
            ->setParameter('id', $id)
            ->getQuery()->execute();
    }
}
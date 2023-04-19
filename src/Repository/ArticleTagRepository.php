<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleTagRepository {

    private EntityManagerInterface $entityManager;
    private ArticleRepository $articleRepository;
    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository) { 
        
        $this->entityManager = $entityManager; 
        $this->articleRepository = $articleRepository;   
    }

                /* ----------------------------------------------------------------------- */
                /* ----------------------------------------------------------------------- */

    public function findAllArticlesByQuery(string $queryName, UserInterface|null $user = null) {

        $articles = []; // Permettra de récupérer tous les articles

        /** @var User */
        $user = $user;

            /* ------------------------------------------------ */

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('article_id', 'article_id');
        $rsm->addScalarResult('tag_id', 'tag_id');
        
        $query = "SELECT * FROM article_tag
                INNER JOIN article ON article.id = article_id
                INNER JOIN tag ON tag.id = tag_id 
                WHERE tag.title = :tagTitle OR article.title = :articleTitle";
        
        if($user) $query .= " AND user_id = ". $user->getId();

        $query .= " ORDER BY date DESC";

                       /* ------------------------------------------------ */     

        $query = $this->entityManager->createNativeQuery($query, $rsm);
        $query->setParameter('tagTitle', $queryName);
        $query->setParameter('articleTitle', $queryName);

        if(empty($query->getResult())) { 

            $criteria['title'] = $queryName;
            if($user) { $criteria['user'] = $user; }

                       /* ------------------------------------------------ */     

            $result = $this->articleRepository->findBy($criteria, ['date' => 'DESC']);

        } else { $result = $query->getResult(); }

            /* ------------------------------------------------ */

        foreach($result as $value) {
            
            if(is_array($value)) { $articles[] = $this->articleRepository->find($value['article_id']); }
            else $articles[] = $value;
        }   

            /* ------------------------------------------------ */


        return array_unique($articles, SORT_REGULAR);
    }
}

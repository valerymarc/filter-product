<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    
    /**
     * Undocumented variable
     *
     * @var PaginatorInterface
     */
    private $paginator;
    
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
        $this->paginator = $paginator;
    }


   /**
    * Undocumented function
    *
    * @return PaginationInterface
    */
   public function findSearch(SearchData $search):PaginationInterface
   {     
       $query = $this->getSearchQuery($search)->getQuery();
       return $this->paginator->paginate($query, $search->page, 9);
   }


   /**
    * Récupère le prix minimun et maximum correspondant à une recherche
    *
    * @param SearchData $search
    * @return integer[]
    */
   public function findMinMax(SearchData $search)
   {
    $result = $this->getSearchQuery($search, true)
          ->select('MIN(p.prix) as min', 'MAX(p.prix) as max')
          ->getQuery()
          ->getScalarResult();
    return [(int)$result[0]['min'], (int)$result[0]['max']];
   }


   private function getSearchQuery(SearchData $search, $ignorePrix=false): QueryBuilder
   {
    $query = $this
    ->createQueryBuilder('p')
    ->select('c', 'p')
    ->join('p.categories', 'c');

    if(!empty($search->q)){
        $query = $query
           ->andWhere('p.nom LIKE :q')
           ->setParameter('q', "%{$search->q}%");
    }

    if(!empty($search->min) && $ignorePrix == false){
        $query = $query
           ->andWhere('p.prix >= :min')
           ->setParameter('min', $search->min);
    }

    if(!empty($search->max) && $ignorePrix == false){
        $query = $query
           ->andWhere('p.prix <= :max')
           ->setParameter('max', $search->max);
    }

    if(!empty($search->promo)){
        $query = $query
           ->andWhere('p.promo = 1');
    }

    if(!empty($search->categories)){
        $query = $query
           ->andWhere('c.id IN (:categories)')
           ->setParameter('categories', $search->categories);
    }

    return $query;
   }
   


    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

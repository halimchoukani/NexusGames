<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private $entityManager;
    public function __construct(ManagerRegistry $registry , EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Product::class);
        $this->entityManager = $entityManager;
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    public function getAll(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findNewProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.date_insertion', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }
    public function getById($id): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function editQuantitie($id, $quantity): void
    {
        $product = $this->find($id);
        if ($product === null) {
            throw new \Exception('Product not found.');
        }

        $currentQuantity = $product->getQuantite    ();
        if ($currentQuantity < $quantity) {
            throw new \Exception('Insufficient product quantity.');
        }

        $product->setQuantite($currentQuantity - $quantity);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function getByMarque($marque): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.marque = :val')
            ->setParameter('val', $marque)
            ->getQuery()
            ->getResult();
    }

}

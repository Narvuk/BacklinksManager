<?PHP
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Sites;

class SitesRepository extends EntityRepository
{
    public function loadLatestSitesHome($limit)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.id > :id')
            ->setParameter('id', 'a.id')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery();

        return $qb->execute();
    }
}
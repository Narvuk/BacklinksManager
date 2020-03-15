<?PHP
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Sitepages;

class SitepagesRepository extends EntityRepository
{
    public function loadSitepagesByID($id)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.id > :id')
            ->setParameter('id', $id)
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        return $qb->execute();
    }

}
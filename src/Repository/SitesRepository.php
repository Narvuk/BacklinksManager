<?PHP
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Sites;

class SitesRepository extends EntityRepository
{
    public function loadSitesByName($name)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.name > :name')
            ->setParameter('name', $name)
            ->orderBy('a.name', 'ASC')
            ->getQuery();

        return $qb->execute();
    }
}
<?PHP
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Prospects;

class ProspectsRepository extends EntityRepository
{
    public function loadPospectsByID($id)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.id > :id')
            ->setParameter('id', $id)
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        return $qb->execute();
    }

}
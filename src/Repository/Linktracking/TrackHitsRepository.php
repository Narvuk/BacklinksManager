<?PHP
namespace App\Repository\Linktracking;

use Doctrine\ORM\EntityRepository;
use App\Entity\Linktracking\TrackHits;

class TrackHitsRepository extends EntityRepository
{
    public function loadHitsByID($id)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.id > :id')
            ->setParameter('id', $id)
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        return $qb->execute();
    }

}
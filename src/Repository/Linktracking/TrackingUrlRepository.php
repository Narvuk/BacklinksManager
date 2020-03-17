<?PHP
namespace App\Repository\Linktracking;

use Doctrine\ORM\EntityRepository;
use App\Entity\Linktracking\TrackingUrl;

class TrackingUrlRepository extends EntityRepository
{
    public function loadTrackingUrlByID($id)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.id > :id')
            ->setParameter('id', $id)
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        return $qb->execute();
    }

}
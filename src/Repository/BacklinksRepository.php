<?PHP
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Backlinks;

class BacklinksRepository extends EntityRepository
{
    public function loadBacklinksByID($id)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.id > :id')
            ->setParameter('id', $id)
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        return $qb->execute();
    }

}
<?PHP
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Keywords;

class KeywordsRepository extends EntityRepository
{
    public function loadKeywordsByID($id)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.id > :id')
            ->setParameter('id', $id)
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        return $qb->execute();
    }

}
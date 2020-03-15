<?PHP
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Linkingdomains;

class LinkingdomainsRepository extends EntityRepository
{
    public function loadlinkingDomainsByID($id)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.id > :id')
            ->setParameter('id', $id)
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        return $qb->execute();
    }

}
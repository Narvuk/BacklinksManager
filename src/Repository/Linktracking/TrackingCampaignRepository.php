<?PHP
namespace App\Repository\Linktracking;

use Doctrine\ORM\EntityRepository;
use App\Entity\Linktracking\TrackingCampaign;

class TrackingCampaignRepository extends EntityRepository
{
    public function loadCampaignByID($id)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.id > :id')
            ->setParameter('id', $id)
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        return $qb->execute();
    }

}
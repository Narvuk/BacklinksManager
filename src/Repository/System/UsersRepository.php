<?PHP
namespace App\Repository\System;

use Doctrine\ORM\EntityRepository;
use App\Entity\System\Users;

class UsersRepository extends EntityRepository
{
    public function loadUserByUsername($username)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.username > :username')
            ->setParameter('username', $username)
            ->orderBy('a.username', 'ASC')
            ->getQuery();

        return $qb->execute();
    }
}
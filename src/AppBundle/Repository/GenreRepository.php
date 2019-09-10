<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Genre;
use AppBundle\Entity\Title;
use Doctrine\ORM\EntityRepository;
use Nines\UserBundle\Entity\User;

/**
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GenreRepository extends EntityRepository {

    /**
     * Execute a name search for a typeahead widget.
     *
     * @param string $q
     *
     * @return mixed
     */
    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere("e.name LIKE :q");
        $qb->orderBy('e.name');
        $qb->setParameter('q', "{$q}%");
        return $qb->getQuery()->execute();
    }

    /**
     * Count the titles in a genre.
     *
     * @param Genre $genre
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countTitles(Genre $genre, User $user = null) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(title.id)');
        $qb->andWhere('title.genre = :genre');
        if( ! $user) {
            $qb->andWhere('title.finalattempt = 1 OR title.finalcheck = 1');
        }
        $qb->setParameter('genre', $genre);
        $qb->from(Title::class, 'title');
        return $qb->getQuery()->getSingleScalarResult();
    }

}

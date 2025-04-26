<?php
/*
 *  Copyright 2025.  Baks.dev <admin@baks.dev>
 *  
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *  
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *  
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

namespace BaksDev\Users\Profile\TypeProfile\Repository\AllProfileType;

use BaksDev\Core\Doctrine\DBALQueryBuilder;
use BaksDev\Core\Doctrine\ORMQueryBuilder;
use BaksDev\Core\Form\Search\SearchDTO;
use BaksDev\Core\Services\Paginator\PaginatorInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Entity\Info\TypeProfileInfo;
use BaksDev\Users\Profile\TypeProfile\Entity\Trans\TypeProfileTrans;
use BaksDev\Users\Profile\TypeProfile\Entity\TypeProfile;

final class AllProfileTypeRepository implements AllProfileTypeInterface
{

    private ?SearchDTO $search = null;

    public function __construct(
        private readonly DBALQueryBuilder $DBALQueryBuilder,
        private readonly ORMQueryBuilder $ORMQueryBuilder,
        private readonly PaginatorInterface $paginator,
    ) {}

    public function search(SearchDTO $search): self
    {
        $this->search = $search;
        return $this;
    }

    public function find(): PaginatorInterface
    {
        $qb = $this->DBALQueryBuilder->createQueryBuilder(self::class);
        $qb->bindLocal();

        /* TypeProfile */
        $qb->select('profile.id');
        $qb->addSelect('profile.event');

        $qb->from(TypeProfile::class, 'profile');

        /* TypeProfile Event */
        $qb->addSelect('profile_event.sort');
        $qb->join(
            'profile',
            TypeProfileEvent::class,
            'profile_event',
            'profile_event.id = profile.event'
        );

        /* TypeProfile Translate */
        $qb
            ->addSelect('profile_trans.name')
            ->addSelect('profile_trans.description')
            ->join(
                'profile',
                TypeProfileTrans::class,
                'profile_trans',
                'profile_trans.event = profile.event AND profile_trans.local = :local'
            );

        $qb
            ->addSelect('info.active')
            ->addSelect('info.public')
            ->addSelect('info.usr')
            ->leftJoin(
                'profile',
                TypeProfileInfo::class,
                'info',
                'info.profile = profile.id'
            );


        $qb->orderBy('profile_event.sort', 'ASC');


        if($this->search?->getQuery())
        {
            $qb
                ->createSearchQueryBuilder($this->search)
                ->addSearchEqualUid('profile.id')
                ->addSearchEqualUid('profile.event')
                //                ->addSearchLike('product_trans.name')
                //->addSearchLike('product_trans.preview')
                ->addSearchLike('profile_trans.name')
                ->addSearchLike('profile_trans.description');
        }


        return $this->paginator->fetchAllAssociative($qb);

    }

    public function getTypeProfile(): ?array
    {
        $qb = $this->ORMQueryBuilder
            ->createQueryBuilder(self::class)
            ->bindLocal();

        /* TypeProfile */
        $qb
            ->select('profile.id')
            ->addSelect('profile.event')
            ->from(TypeProfile::class, 'profile');

        /* TypeProfile Event */
        $qb
            ->addSelect('profile_event.sort')
            ->join(
                TypeProfileEvent::class,
                'profile_event',
                'WITH',
                'profile_event.id = profile.event'
            );

        /* TypeProfile Translate */
        $qb
            ->addSelect('profile_trans.name')
            ->addSelect('profile_trans.description')
            ->join(
                TypeProfileTrans::class,
                'profile_trans',
                'WITH',
                'profile_trans.event = profile.event AND profile_trans.local = :local'
            );

        $qb->orderBy('profile_event.sort', 'ASC');

        return $qb->getResult();
    }

}

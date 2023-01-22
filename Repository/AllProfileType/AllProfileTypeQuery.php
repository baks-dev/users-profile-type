<?php
/*
 *  Copyright 2023.  Baks.dev <admin@baks.dev>
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
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 *
 *
 */

namespace BaksDev\Users\Profile\TypeProfile\Repository\AllProfileType;

use BaksDev\Users\Profile\TypeProfile\Entity as TypeProfileEntity;
use BaksDev\Users\Profile\TypeProfile\Repository\AllProfileType\AllProfileTypeInterface;

use BaksDev\Core\Form\Search\SearchDTO;
use BaksDev\Core\Services\Paginator\PaginatorInterface;
use BaksDev\Core\Services\Switcher\SwitcherInterface;
use BaksDev\Core\Type\Locale\Locale;
use Doctrine\DBAL\Connection;
use Symfony\Contracts\Translation\TranslatorInterface;

final class AllProfileTypeQuery implements AllProfileTypeInterface
{
	
	private Connection $connection;
	private Locale $local;
	private PaginatorInterface $paginator;
	private SwitcherInterface $switcher;
	
	public function __construct(
		Connection $connection,
		TranslatorInterface $translator,
		PaginatorInterface $paginator,
		SwitcherInterface $switcher
	)
	{
		$this->connection = $connection;
		$this->local = new Locale($translator->getLocale());
		$this->paginator = $paginator;
		$this->switcher = $switcher;
	}
    
    public function get(SearchDTO $search = null)
    {
        $qb = $this->connection->createQueryBuilder();

        /* TypeProfile */
        $qb->select('profile.id');
        $qb->addSelect('profile.event');
        
        $qb->from(TypeProfileEntity\TypeProfile::TABLE, 'profile');
	
	
		
        /* TypeProfile Event */
        $qb->addSelect('profile_event.sort');
        $qb->join('profile', TypeProfileEntity\Event\TypeProfileEvent::TABLE, 'profile_event', 'profile_event.id = profile.event');
    
        /* TypeProfile Translate */
        $qb->addSelect('profile_trans.name');
        $qb->addSelect('profile_trans.description');
        $qb->join(
          'profile',
			TypeProfileEntity\Trans\TypeProfileTrans::TABLE,
          'profile_trans',
          'profile_trans.event = profile.event AND profile_trans.local = :local');
		
        $qb->setParameter('local', $this->local, Locale::TYPE);
	
		
        /* Поиск */
        if($search && $search->query)
        {
            /*            $qb->andWhere(
                          '
                                    LOWER(profile_trans.name) LIKE :query OR
            
                                    LOWER(profile_trans.name) LIKE :switcher
            
                                ');
                        
                        $qb->setParameter('query', '%'.mb_strtolower((new Switcher())($search->query, 0)).'%');
                        $qb->setParameter('switcher', '%'.mb_strtolower((new Switcher())($search->query, 1)).'%');
            */
        }
	
		$qb->orderBy('profile_event.sort', 'ASC');
		return $this->paginator->fetchAllAssociative($qb);

    }
    
}
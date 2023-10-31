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
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

declare(strict_types=1);

namespace BaksDev\Users\Profile\TypeProfile\Repository\TypeProfileChoice;

use BaksDev\Core\Doctrine\ORMQueryBuilder;
use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Users\Profile\TypeProfile\Entity as TypeProfileEntity;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use Symfony\Contracts\Translation\TranslatorInterface;

final class TypeProfileChoice implements TypeProfileChoiceInterface
{
    private TranslatorInterface $translator;
    private ORMQueryBuilder $ORMQueryBuilder;


    public function __construct(ORMQueryBuilder $ORMQueryBuilder, TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->ORMQueryBuilder = $ORMQueryBuilder;
    }


    public function getTypeProfileChoice()
    {

        $qb = $this->ORMQueryBuilder->createQueryBuilder(self::class);

        $select = sprintf('new %s(type.id, type_trans.name, type_trans.description)', TypeProfileUid::class);
        $qb->select($select);

        $qb->from(TypeProfileEntity\TypeProfile::class, 'type');

        $qb->join(TypeProfileEntity\Event\TypeProfileEvent::class, 'type_event', 'WITH', 'type_event.id = type.event');

        $qb->leftJoin(TypeProfileEntity\Trans\TypeProfileTrans::class,
            'type_trans',
            'WITH',
            'type_trans.event = type_event.id AND type_trans.local = :local'
        );

        $qb->addOrderBy('type_event.sort');

        $qb->setParameter('local', new Locale($this->translator->getLocale()), Locale::TYPE);

        /* Кешируем результат ORM */
        return $qb->enableCache('users-profile-type', 86400)->getResult();

    }

}
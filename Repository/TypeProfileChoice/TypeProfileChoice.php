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

use BaksDev\Core\Doctrine\DBALQueryBuilder;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Entity\Info\TypeProfileInfo;
use BaksDev\Users\Profile\TypeProfile\Entity\Trans\TypeProfileTrans;
use BaksDev\Users\Profile\TypeProfile\Entity\TypeProfile;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use BaksDev\Users\Profile\UserProfile\Type\Id\UserProfileUid;
use Generator;

final class TypeProfileChoice implements TypeProfileChoiceInterface
{

    private DBALQueryBuilder $DBALQueryBuilder;

    public function __construct(DBALQueryBuilder $DBALQueryBuilder)
    {
        $this->DBALQueryBuilder = $DBALQueryBuilder;
    }

    public function getAllTypeProfileChoice(): Generator
    {
        $dbal = $this->getQueryBuilder();

        $dbal->andWhereExists(
            TypeProfileInfo::class,
            'info',
            'info.profile = profile.id'
        );

        return $dbal
            ->enableCache('users-profile-type', 86400)
            ->fetchAllHydrate(TypeProfileUid::class);
    }

    public function getActiveTypeProfileChoice(): Generator
    {
        $dbal = $this->getQueryBuilder();

        $dbal->andWhereExists(
            TypeProfileInfo::class,
            'info',
            'info.profile = profile.id AND info.active = true'
        );

        return $dbal
            ->enableCache('users-profile-type', 86400)
            ->fetchAllHydrate(TypeProfileUid::class);

    }

    public function getPublicTypeProfileChoice(): Generator
    {
        $dbal = $this->getQueryBuilder();

        $dbal->andWhereExists(
            TypeProfileInfo::class,
            'info',
            'info.profile = profile.id AND info.public = true'
        );

        return $dbal
            ->enableCache('users-profile-type', 86400)
            ->fetchAllHydrate(TypeProfileUid::class);

    }

    public function getUsersTypeProfileChoice(): Generator
    {
        $dbal = $this->getQueryBuilder();

        $dbal->andWhereExists(
            TypeProfileInfo::class,
            'info',
            'info.profile = profile.id AND info.usr = true'
        );

        return $dbal
            //->enableCache('users-profile-type', 86400)
            ->fetchAllHydrate(TypeProfileUid::class);
    }



    private function getQueryBuilder(): DBALQueryBuilder
    {
        $dbal = $this->DBALQueryBuilder
            ->createQueryBuilder(self::class)
            ->bindLocal();

        $dbal->from(TypeProfile::TABLE, 'profile');

        $dbal->join('profile',
            TypeProfileEvent::TABLE,
            'profile_event',
            'profile_event.id = profile.event'
        );

        $dbal->join(
            'profile',
            TypeProfileTrans::TABLE,
            'profile_trans',
            'profile_trans.event = profile.event AND profile_trans.local = :local'
        );



        $dbal->orderBy('profile_event.sort', 'ASC');

        $dbal->addSelect('profile.id AS value');
        $dbal->addSelect('profile_trans.name AS attr');
        $dbal->addSelect('profile_trans.description AS option');

        return $dbal;
    }


}
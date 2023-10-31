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

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\Delete\Tests;

use BaksDev\Core\Doctrine\ORMQueryBuilder;
use BaksDev\Core\Type\Field\InputField;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Entity\TypeProfile;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\Delete\DeleteTypeProfileDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\Delete\DeleteTypeProfileHandler;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\SectionFieldDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\Trans\SectionFieldTransDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\SectionDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Trans\SectionTransDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Tests\EditTypeProfileHandleTest;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Trans\TransDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\TypeProfileDTO;
use BaksDev\Wildberries\Products\Entity\Barcode\Event\WbBarcodeEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Attribute\When;

/**
 * @group users-profile-type
 * @group users-profile-type-usecase
 *
 * @depends BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Tests\EditTypeProfileHandleTest::class
 * @see     EditTypeProfileHandleTest
 *
 */
#[When(env: 'test')]
final class DeleteTypeProfileTest extends KernelTestCase
{

    public function testUseCase(): void
    {

        self::bootKernel();
        $container = self::getContainer();

        /** @var ORMQueryBuilder $ORMQueryBuilder */
        $ORMQueryBuilder = $container->get(ORMQueryBuilder::class);
        $qb = $ORMQueryBuilder->createQueryBuilder(self::class);

        $qb
            ->from(TypeProfile::class, 'main')
            ->where('main.id = :main')
            ->setParameter('main', TypeProfileUid::TEST, TypeProfileUid::TYPE);

        $qb
            ->select('event')
            ->leftJoin(TypeProfileEvent::class,
                'event',
                'WITH',
                'event.id = main.event'
            );


        /** @var WbBarcodeEvent $WbBarcodeEvent */
        $Event = $qb->getQuery()->getOneOrNullResult();


        /** @var TypeProfileDTO $TypeProfileDTO */
        $TypeProfileDTO = $Event->getDto(TypeProfileDTO::class);




        self::assertEquals(TypeProfileUid::TEST, (string) $TypeProfileDTO->getProfile());
        self::assertEquals(321, $TypeProfileDTO->getSort());


        /** @var TransDTO $TransDTO */
        foreach($TypeProfileDTO->getTranslate() as $TransDTO)
        {
            self::assertEquals('TransNameEdit', $TransDTO->getName());
            self::assertEquals('TransDescEdit', $TransDTO->getDescription());
        }


        /** @var SectionDTO $SectionDTO */

        $SectionDTO = $TypeProfileDTO->getSection()->current();

        self::assertEquals(123, $SectionDTO->getSort());


        /** @var SectionTransDTO $SectionTransDTO */
        foreach($SectionDTO->getTranslate() as $SectionTransDTO)
        {
            self::assertEquals('SectionTransNameEdit', $SectionTransDTO->getName());

            self::assertEquals('SectionTransDescriptionEdit', $SectionTransDTO->getDescription());

        }

        /** @var SectionFieldDTO $SectionFieldDTO */
        $SectionFieldDTO = $SectionDTO->getField()->current();

        $InputField = new InputField('integer_field');
        self::assertEquals($InputField, $SectionFieldDTO->getType());
        self::assertEquals(386, $SectionFieldDTO->getSort());
        self::assertFalse($SectionFieldDTO->getPublic());
        self::assertFalse($SectionFieldDTO->getRequired());
        self::assertFalse($SectionFieldDTO->getCard());

        /** @var SectionFieldTransDTO $SectionFieldTransDTO */
        foreach($SectionFieldDTO->getTranslate() as $SectionFieldTransDTO)
        {
            self::assertEquals('SectionFieldTransNameNew', $SectionFieldTransDTO->getName());
            self::assertEquals('SectionFieldTransDescriptionNew', $SectionFieldTransDTO->getDescription());
        }



        /** DELETE */

        /** @var DeleteTypeProfileDTO $DeleteTypeProfileDTO */
        $DeleteTypeProfileDTO = $Event->getDto(DeleteTypeProfileDTO::class);

        /** @var DeleteTypeProfileHandler $DeleteTypeProfileHandler */
        $DeleteTypeProfileHandler = $container->get(DeleteTypeProfileHandler::class);
        $handle = $DeleteTypeProfileHandler->handle($DeleteTypeProfileDTO);
        self::assertTrue(($handle instanceof TypeProfile), $handle.': Ошибка TypeProfile');


        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);

        $TypeProfile = $em->getRepository(TypeProfile::class)
            ->find(TypeProfileUid::TEST);
        self::assertNull($TypeProfile);

    }

    /**
     * @depends testUseCase
     */
    public function testComplete(): void
    {

        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);

        /* WbBarcode */

        $TypeProfile = $em->getRepository(TypeProfile::class)
            ->find(TypeProfileUid::TEST);

        if($TypeProfile)
        {
            $em->remove($TypeProfile);
        }

        /* WbBarcodeEvent */

        $TypeProfileEventCollection = $em->getRepository(TypeProfileEvent::class)
            ->findBy(['profile' => TypeProfileUid::TEST]);

        foreach($TypeProfileEventCollection as $remove)
        {
            $em->remove($remove);
        }

        $em->flush();

        self::assertNull($TypeProfile);
    }

}

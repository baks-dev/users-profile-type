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

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Tests;

use BaksDev\Core\Type\Field\InputField;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Entity\TypeProfile;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\SectionFieldDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\Trans\SectionFieldTransDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\SectionDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Trans\SectionTransDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Trans\TransDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\TypeProfileDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\TypeProfileHandler;
use BaksDev\Wildberries\Package\UseCase\Package\Pack\Orders\WbPackageOrderDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Attribute\When;

/**
 * @group users-profile-type
 * @group users-profile-type-usecase
 */
#[When(env: 'test')]
final class NewTypeProfileHandleTest extends KernelTestCase
{
    public static function setUpBeforeClass(): void
    {
        //        /** @var $WbPackageStatusCollection $WbPackageStatusCollection */
        //        $WbPackageStatusCollection = self::getContainer()->get(WbPackageStatusCollection::class);
        //        $WbPackageStatusCollection->cases();

        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);

        $Main = $em->getRepository(TypeProfile::class)
            ->findOneBy(['id' => TypeProfileUid::TEST]);

        if($Main)
        {
            $em->remove($Main);
        }

        /* WbBarcodeEvent */

        $Event = $em->getRepository(TypeProfileEvent::class)
            ->findBy(['profile' => TypeProfileUid::TEST]);



        foreach($Event as $remove)
        {
            $em->remove($remove);
        }

        $em->flush();
    }


    public function testUseCase(): void
    {

        /** @var WbPackageOrderDTO $WbPackageOrderDTO */

        $TypeProfileDTO = new TypeProfileDTO();

        $TypeProfileUid = new TypeProfileUid();
        $TypeProfileDTO->setProfile($TypeProfileUid);
        self::assertSame($TypeProfileUid, $TypeProfileDTO->getProfile());

        $TypeProfileDTO->setSort(123);
        self::assertEquals(123, $TypeProfileDTO->getSort());


        /** @var TransDTO $TransDTO */
        foreach($TypeProfileDTO->getTranslate() as $TransDTO)
        {
            $TransDTO->setName('TransName');
            self::assertEquals('TransName', $TransDTO->getName());
            $TransDTO->setDescription('TransDesc');
            self::assertEquals('TransDesc', $TransDTO->getDescription());
        }


        /** @var SectionDTO $SectionDTO */

        $SectionDTO = new SectionDTO();
        $SectionDTO->setSort(321);
        self::assertEquals(321, $SectionDTO->getSort());


        /** @var SectionTransDTO $SectionTransDTO */
        foreach($SectionDTO->getTranslate() as $SectionTransDTO)
        {
            $SectionTransDTO->setName('SectionTransName');
            self::assertEquals('SectionTransName', $SectionTransDTO->getName());

            $SectionTransDTO->setDescription('SectionTransDescription');
            self::assertEquals('SectionTransDescription', $SectionTransDTO->getDescription());
        }


        /** @var SectionFieldDTO $SectionFieldDTO */
        $SectionFieldDTO = new SectionFieldDTO();

        $InputField = new InputField('input_field');
        $SectionFieldDTO->setType($InputField);
        self::assertSame($InputField, $SectionFieldDTO->getType());

        $SectionFieldDTO->setSort(683);
        self::assertEquals(683, $SectionFieldDTO->getSort());


        $SectionFieldDTO->setPublic(false);
        self::assertFalse($SectionFieldDTO->getPublic());
        $SectionFieldDTO->setPublic(true);
        self::assertTrue($SectionFieldDTO->getPublic());


        $SectionFieldDTO->setRequired(false);
        self::assertFalse($SectionFieldDTO->getRequired());
        $SectionFieldDTO->setRequired(true);
        self::assertTrue($SectionFieldDTO->getRequired());


        $SectionFieldDTO->setCard(false);
        self::assertFalse($SectionFieldDTO->getCard());
        $SectionFieldDTO->setCard(true);
        self::assertTrue($SectionFieldDTO->getCard());


        /** @var SectionFieldTransDTO $SectionFieldTransDTO */
        foreach($SectionFieldDTO->getTranslate() as $SectionFieldTransDTO)
        {
            $SectionFieldTransDTO->setName('SectionFieldTransName');
            self::assertEquals('SectionFieldTransName', $SectionFieldTransDTO->getName());

            $SectionFieldTransDTO->setDescription('SectionFieldTransDescription');
            self::assertEquals('SectionFieldTransDescription', $SectionFieldTransDTO->getDescription());

        }


        $TypeProfileDTO->addSection($SectionDTO);
        self::assertEquals(1, $TypeProfileDTO->getSection()->count());


        $SectionDTO->addField($SectionFieldDTO);
        self::assertEquals(1, $SectionDTO->getField()->count());

        /** @var TypeProfileHandler $TypeProfileHandler */
        $TypeProfileHandler = self::getContainer()->get(TypeProfileHandler::class);
        $handle = $TypeProfileHandler->handle($TypeProfileDTO);

        self::assertTrue(($handle instanceof TypeProfile), $handle.': Ошибка WbPackage');

    }

    public function testComplete(): void
    {
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $TypeProfile = $em->getRepository(TypeProfile::class)
            ->find(TypeProfileUid::TEST);
        self::assertNotNull($TypeProfile);
    }
}
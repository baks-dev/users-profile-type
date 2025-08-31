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

declare(strict_types=1);

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Tests;

use BaksDev\Core\Type\Field\InputField;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Entity\TypeProfile;
use BaksDev\Users\Profile\TypeProfile\Type\Event\TypeProfileEventUid;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\SectionFieldDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\Trans\SectionFieldTransDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\SectionDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Trans\SectionTransDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Trans\TransDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\TypeProfileDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\TypeProfileHandler;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Attribute\When;

#[Group('users-profile-type')]
#[When(env: 'test')]
final class EditTypeProfileHandleTest extends KernelTestCase
{
    #[DependsOnClass(NewTypeProfileHandleTest::class)]
    public function testUseCase(): void
    {
        $container = self::getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);

        $Event = $em->getRepository(TypeProfileEvent::class)
            ->find(TypeProfileEventUid::TEST);

        self::assertNotNull($Event);


        /** @var TypeProfileDTO $TypeProfileDTO */
        $TypeProfileDTO = $Event->getDto(TypeProfileDTO::class);


        self::assertEquals(TypeProfileUid::TEST, (string) $TypeProfileDTO->getProfile());
        self::assertEquals(123, $TypeProfileDTO->getSort());
        $TypeProfileDTO->setSort(321);

        $TypeProfileDTO->setProfile(clone $TypeProfileDTO->getProfile());
        self::assertEquals(TypeProfileUid::TEST, (string) $TypeProfileDTO->getProfile());


        /** @var TransDTO $TransDTO */
        foreach($TypeProfileDTO->getTranslate() as $TransDTO)
        {
            self::assertEquals('TransName', $TransDTO->getName());
            $TransDTO->setName('TransNameEdit');

            self::assertEquals('TransDesc', $TransDTO->getDescription());
            $TransDTO->setDescription('TransDescEdit');
        }


        /** @var SectionDTO $SectionDTO */

        $SectionDTO = $TypeProfileDTO->getSection()->current();

        self::assertEquals(321, $SectionDTO->getSort());
        $SectionDTO->setSort(123);


        /** @var SectionTransDTO $SectionTransDTO */
        foreach($SectionDTO->getTranslate() as $SectionTransDTO)
        {
            self::assertEquals('SectionTransName', $SectionTransDTO->getName());
            $SectionTransDTO->setName('SectionTransNameEdit');

            self::assertEquals('SectionTransDescription', $SectionTransDTO->getDescription());
            $SectionTransDTO->setDescription('SectionTransDescriptionEdit');

        }


        /** @var SectionFieldDTO $SectionFieldDTO */
        $SectionFieldDTO = $SectionDTO->getField()->current();

        $InputField = new InputField('input_field');
        self::assertEquals($InputField, $SectionFieldDTO->getType());
        self::assertEquals(683, $SectionFieldDTO->getSort());
        self::assertTrue($SectionFieldDTO->getPublic());
        self::assertTrue($SectionFieldDTO->getRequired());
        self::assertTrue($SectionFieldDTO->getCard());

        /** @var SectionFieldTransDTO $SectionFieldTransDTO */
        foreach($SectionFieldDTO->getTranslate() as $SectionFieldTransDTO)
        {
            self::assertEquals('SectionFieldTransName', $SectionFieldTransDTO->getName());
            self::assertEquals('SectionFieldTransDescription', $SectionFieldTransDTO->getDescription());
        }


        /* Удаляем филд и создаем новый  */
        $SectionDTO->removeField($SectionFieldDTO);

        /** @var SectionFieldDTO $NewSectionFieldDTO */
        $NewSectionFieldDTO = new SectionFieldDTO();
        //$NewSectionFieldDTO->newSectionField();

        $IntegerField = new InputField('integer_field');
        $NewSectionFieldDTO->setType($IntegerField);

        $NewSectionFieldDTO->setSort(386);
        $NewSectionFieldDTO->setPublic(false);
        $NewSectionFieldDTO->setRequired(false);
        $NewSectionFieldDTO->setCard(false);

        /** @var SectionFieldTransDTO $SectionFieldTransDTO */
        foreach($NewSectionFieldDTO->getTranslate() as $SectionFieldTransDTO)
        {
            $SectionFieldTransDTO->setName('SectionFieldTransNameNew');
            $SectionFieldTransDTO->setDescription('SectionFieldTransDescriptionNew');
        }

        $SectionDTO->addField($NewSectionFieldDTO);
        self::assertEquals(1, $SectionDTO->getField()->count());

        /** @var TypeProfileHandler $TypeProfileHandler */
        $TypeProfileHandler = self::getContainer()->get(TypeProfileHandler::class);
        $handle = $TypeProfileHandler->handle($TypeProfileDTO);

        self::assertTrue(($handle instanceof TypeProfile), $handle.': Ошибка WbPackage');

        $em->clear();
        //$em->close();
    }

}

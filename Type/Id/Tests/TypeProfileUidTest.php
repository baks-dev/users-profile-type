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

namespace BaksDev\Users\Profile\TypeProfile\Type\Id\Tests;

use BaksDev\Orders\Order\Type\Status\OrderStatus;
use BaksDev\Orders\Order\Type\Status\OrderStatus\Collection\OrderStatusCollection;
use BaksDev\Orders\Order\Type\Status\OrderStatusType;
use BaksDev\Users\Profile\TypeProfile\Type\Id\Choice\Collection\TypeProfileCollection;
use BaksDev\Users\Profile\TypeProfile\Type\Id\Choice\Collection\TypeProfileInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileType;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use BaksDev\Wildberries\Orders\Type\WildberriesStatus\Status\Collection\WildberriesStatusInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Attribute\When;

/**
 * @group users-profile-type
 */
#[When(env: 'test')]
final class TypeProfileUidTest extends KernelTestCase
{
    public function testUseCase(): void
    {
        /** @var TypeProfileCollection $TypeProfileCollection */
        $TypeProfileCollection = self::getContainer()->get(TypeProfileCollection::class);

        /** @var TypeProfileInterface $case */
        foreach($TypeProfileCollection->cases() as $case)
        {

            $TypeProfileUid = new TypeProfileUid($case->getValue());

            self::assertTrue($TypeProfileUid->equals((string) $case)); // объект интерфейса
            self::assertTrue($TypeProfileUid->equals($case->getValue())); // срока
            self::assertTrue($TypeProfileUid->equals($TypeProfileUid)); // объект класса

            $TypeProfileType = new TypeProfileType();
            $platform = $this->getMockForAbstractClass(AbstractPlatform::class);

            $convertToDatabase = $TypeProfileType->convertToDatabaseValue($TypeProfileUid, $platform);
            self::assertEquals($TypeProfileUid->getTypeProfileValue(), $convertToDatabase);

            $convertToPHP = $TypeProfileType->convertToPHPValue($convertToDatabase, $platform);
            self::assertInstanceOf(TypeProfileUid::class, $convertToPHP);
            self::assertEquals($case, $convertToPHP->getTypeProfile());

        }

        self::assertTrue(true);
    }
}
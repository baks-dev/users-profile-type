<?php
/*
 *  Copyright 2024.  Baks.dev <admin@baks.dev>
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

namespace BaksDev\Users\Profile\TypeProfile\Type\Id;

use BaksDev\Core\Type\UidType\Uid;
use BaksDev\Users\Profile\TypeProfile\Type\Id\Choice\Collection\TypeProfileInterface;
use Symfony\Component\Uid\AbstractUid;

final class TypeProfileUid extends Uid
{
    public const TYPE = 'profile';

    public const TEST = '018ad881-fd9e-73d2-aea3-75c908fbfbb8';

    private mixed $option;

    private mixed $attr;

    public function __construct(
        AbstractUid|TypeProfileInterface|self|string|null $value = null,
        mixed $option = null,
        mixed $attr = null
    )
    {
        if(is_string($value) && class_exists($value))
        {
            $value = new $value();
        }

        if($value instanceof TypeProfileInterface)
        {
            $value = $value->getValue();
        }

        parent::__construct($value);

        $this->option = $option;
        $this->attr = $attr;
    }

    public function getOption(): mixed
    {
        return $this->option;
    }

    public function getAttr(): mixed
    {
        return $this->attr;
    }

    public function getTypeProfileValue(): string
    {
        return (string) $this->getValue();
    }

    public function getTypeProfile(): TypeProfileUid|TypeProfileInterface
    {
        foreach(self::getDeclared() as $declared)
        {
            /** @var TypeProfileInterface $declared */
            if($declared::equals($this->getValue()))
            {
                return new $declared();
            }
        }

        return new self($this->getValue());
    }

    public static function getDeclared(): array
    {
        return array_filter(
            get_declared_classes(),
            static function($className) {
                return in_array(TypeProfileInterface::class, class_implements($className), true);
            }
        );
    }
}

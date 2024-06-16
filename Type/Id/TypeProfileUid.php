<?php
/*
*  Copyright Baks.dev <admin@baks.dev>
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*  http://www.apache.org/licenses/LICENSE-2.0
*
*  Unless required by applicable law or agreed to in writing, software
*  distributed under the License is distributed on an "AS IS" BASIS,
*  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*  See the License for the specific language governing permissions and
*   limitations under the License.
*
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
    ) {
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
            static function ($className) {
                return in_array(TypeProfileInterface::class, class_implements($className), true);
            }
        );
    }
}

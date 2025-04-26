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

namespace BaksDev\Users\Profile\TypeProfile\Entity\Trans;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use InvalidArgumentException;

/* Перевод Profile */


#[ORM\Entity]
#[ORM\Table(name: 'type_users_profile_trans')]
class TypeProfileTrans extends EntityEvent
{
    /** Связь на событие */
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: TypeProfileEvent::class, inversedBy: "translate")]
    #[ORM\JoinColumn(name: 'event', referencedColumnName: "id")]
    private TypeProfileEvent $event;

    /** Локаль */
    #[ORM\Id]
    #[ORM\Column(name: 'local', type: Locale::TYPE, length: 2)]
    private readonly Locale $local;

    /** Название */
    #[ORM\Column(name: 'name', type: Types::STRING, length: 100)]
    private string $name;

    /** Описание */
    #[ORM\Column(name: 'description', type: Types::TEXT, nullable: true)]
    private ?string $description;


    public function __construct(TypeProfileEvent $event)
    {
        $this->event = $event;
    }

    public function __toString(): string
    {
        return (string) $this->event;
    }

    public function getDto($dto): mixed
    {
        $dto = is_string($dto) && class_exists($dto) ? new $dto() : $dto;

        if($dto instanceof TypeProfileTransInterface || $dto instanceof self)
        {
            return parent::getDto($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }


    public function setEntity($dto): mixed
    {

        if($dto instanceof TypeProfileTransInterface || $dto instanceof self)
        {
            return parent::setEntity($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }


    public function equals($dto): bool
    {
        if($dto instanceof TypeProfileTransInterface)
        {
            return ($this->event->getId() === $dto->getEquals() &&
                $dto->getLocal()->getValue() === $this->local->getLocalValue());

        }

        throw new Exception(sprintf('Class %s interface error', $dto::class));
    }


    public function name(Locale $locale): ?string
    {
        if($this->local->getLocalValue() === $locale->getLocalValue())
        {
            return $this->name;
        }

        return null;
    }

}

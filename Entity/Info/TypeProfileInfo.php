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

namespace BaksDev\Users\Profile\TypeProfile\Entity\Info;

use BaksDev\Core\Entity\EntityReadonly;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'type_users_profile_info')]
#[ORM\Index(columns: ['active'])]
#[ORM\Index(columns: ['public'])]
#[ORM\Index(columns: ['usr'])]
class TypeProfileInfo extends EntityReadonly
{
    /** ID Product */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: TypeProfileUid::TYPE)]
    private TypeProfileUid $profile;

    /** ID события */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\OneToOne(targetEntity: TypeProfileEvent::class, inversedBy: 'info')]
    #[ORM\JoinColumn(name: 'event', referencedColumnName: 'id')]
    private TypeProfileEvent $event;


    /** Флаг активности */
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private bool $active = true;

    /** Публичный */
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $public = false;

    /** Пользовательский */
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $usr = false;


    public function __construct(TypeProfileEvent $event)
    {
        $this->event = $event;
        $this->profile = $event->getProfile();
    }

    public function __toString(): string
    {
        return (string) $this->profile;
    }

    public function getProfile(): TypeProfileUid
    {
        return $this->profile;
    }

    //    public function setEvent(ProductEvent $event): self
    //    {
    //        $this->event = $event;
    //        return $this;
    //    }

    public function getDto($dto): mixed
    {
        $dto = is_string($dto) && class_exists($dto) ? new $dto() : $dto;

        if($dto instanceof TypeProfileInfoInterface)
        {
            return parent::getDto($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }

    public function setEntity($dto): mixed
    {
        if($dto instanceof TypeProfileInfoInterface || $dto instanceof self)
        {
            return parent::setEntity($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }

}

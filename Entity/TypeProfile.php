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
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 *
 *
 */

namespace BaksDev\Users\Profile\TypeProfile\Entity;

use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Type\Event\TypeProfileEventUid;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/* Profile Type */


#[ORM\Entity]
#[ORM\Table(name: 'type_users_profile')]
class TypeProfile
{
    public const TABLE = 'type_users_profile';

    /** ID */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: TypeProfileUid::TYPE)]
    private TypeProfileUid $id;

    /** ID События */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Column(type: TypeProfileEventUid::TYPE, unique: true)]
    private TypeProfileEventUid $event;


    /**
     * @param TypeProfileUid $id
     */
    public function __construct()
    {
        $this->id = new TypeProfileUid();
    }


    public function __toString(): string
    {
        return $this->id;
    }

    public function getId(): TypeProfileUid
    {
        return $this->id;
    }

    public function setId(TypeProfileUid $id): void
    {
        $this->id = $id;
    }

    public function getEvent(): TypeProfileEventUid
    {
        return $this->event;
    }

    public function setEvent(TypeProfileEventUid|TypeProfileEvent $event): void
    {
        $this->event = $event instanceof TypeProfileEvent ? $event->getId() : $event;
    }

}

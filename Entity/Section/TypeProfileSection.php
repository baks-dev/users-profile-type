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

namespace BaksDev\Users\Profile\TypeProfile\Entity\Section;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\TypeProfileSectionField;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Trans\TypeProfileSectionTrans;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Id\TypeProfileSectionUid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

/* Section */

#[ORM\Entity]
#[ORM\Table(name: 'type_users_profile_section')]
class TypeProfileSection extends EntityEvent
{
    /** ID */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: TypeProfileSectionUid::TYPE)]
    private TypeProfileSectionUid $id;

    /** Связь на событие Event */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\ManyToOne(targetEntity: TypeProfileEvent::class, inversedBy: "section")]
    #[ORM\JoinColumn(name: 'event', referencedColumnName: "id", nullable: true)]
    private ?TypeProfileEvent $event;

    /** Перевод */
    #[Assert\Valid]
    #[ORM\OneToMany(targetEntity: TypeProfileSectionTrans::class, mappedBy: 'section', cascade: ['all'], fetch: 'EAGER')]
    private Collection $translate;

    /** Поля секции */
    #[Assert\Valid]
    #[ORM\OneToMany(targetEntity: TypeProfileSectionField::class, mappedBy: 'section', cascade: ['all'], fetch: 'EAGER')]
    #[ORM\OrderBy(['sort' => 'ASC'])]
    private Collection $field;

    /** Сортировка */
    #[Assert\NotBlank]
    #[Assert\Length(max: 3)]
    #[Assert\Range(min: 0, max: 999)]
    #[ORM\Column(name: 'sort', type: Types::SMALLINT, options: ['default' => 500])]
    private int $sort = 500;


    public function __construct(TypeProfileEvent $event)
    {
        $this->id = new TypeProfileSectionUid();
        $this->event = $event;

        $this->translate = new ArrayCollection();
        $this->field = new ArrayCollection();

    }

    public function __clone()
    {
        $this->id = clone $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }


    public function getId(): TypeProfileSectionUid
    {
        return $this->id;
    }


    public function getDto($dto): mixed
    {
        $dto = is_string($dto) && class_exists($dto) ? new $dto() : $dto;

        if($dto instanceof TypeProfileSectionInterface)
        {
            return parent::getDto($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }


    public function setEntity($dto): mixed
    {

        if($dto instanceof TypeProfileSectionInterface || $dto instanceof self)
        {
            return parent::setEntity($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }


    private function equals($dto): bool
    {
        if($dto instanceof TypeProfileSectionInterface || $dto instanceof self)
        {
            return $this->id === $dto->getEquals();
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }


    public function removeElement(): void
    {
        $this->event = null;
    }

}

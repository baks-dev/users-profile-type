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

namespace BaksDev\Users\Profile\Type\Entity\Section;


use BaksDev\Users\Profile\Type\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\Type\Entity\Section\Fields\TypeProfileSectionField;
use BaksDev\Users\Profile\Type\Entity\Section\Trans\TypeProfileSectionTrans;
use BaksDev\Users\Profile\Type\Type\Section\Id\TypeProfileSectionUid;
use BaksDev\Users\Profile\Type\Entity\Section\TypeProfileSectionInterface;
use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Locale\Locale;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use InvalidArgumentException;

/* Section */

#[ORM\Entity]
#[ORM\Table(name: 'type_users_profile_section')]
class TypeProfileSection extends EntityEvent
{
    const TABLE = 'type_users_profile_section';
    
    /** ID */
    #[ORM\Id]
    #[ORM\Column(type: TypeProfileSectionUid::TYPE)]
    protected TypeProfileSectionUid $id;
    
    /** Связь на событие Event */
    #[ORM\ManyToOne(targetEntity: TypeProfileEvent::class, cascade: ["remove", "persist"], inversedBy: "section")]
    #[ORM\JoinColumn(name: 'event', referencedColumnName: "id", nullable: true)]
    protected ?TypeProfileEvent $event;
    
    /** Перевод */
    #[ORM\OneToMany(mappedBy: 'section', targetEntity: TypeProfileSectionTrans::class, cascade: ['persist', 'remove'])]
    protected Collection $translate;
    
    /** Поля секции */
    #[ORM\OneToMany(mappedBy: 'section', targetEntity: TypeProfileSectionField::class, cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['sort' => 'ASC'])]
    protected Collection $field;
    
    /** Сортировка */
    #[ORM\Column(name: 'sort', type: Types::SMALLINT, length: 3, nullable: false, options: ['default' => 500])]
    protected int $sort = 500;
    
    
    public function __construct(TypeProfileEvent $event)
    {
        $this->id = new TypeProfileSectionUid();
        $this->translate = new ArrayCollection();
        $this->field = new ArrayCollection();
        $this->event = $event;
    }
    
    /**
     * @return TypeProfileSectionUid
     */
    public function getId() : TypeProfileSectionUid
    {
        return $this->id;
    }
    
    /**
     * @param $dto
     * @return mixed
     * @throws Exception
     */
    public function getDto($dto) : mixed
    {
        if($dto instanceof TypeProfileSectionInterface)
        {
            return parent::getDto($dto);
        }
        
        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }
    
    /**
     * @param $dto
     * @return mixed
     * @throws Exception
     */
    public function setEntity($dto) : mixed
    {
        
        if($dto instanceof TypeProfileSectionInterface)
        {
            return parent::setEntity($dto);
        }
        
        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }
    
    protected function equals($dto) : bool
    {
        if($dto instanceof TypeProfileSectionInterface)
        {
            return $this->id === $dto->getEquals();
        }
        
        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }
    
    public function removeElement() : void
    {
        $this->event = null;
    }
}
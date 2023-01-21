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

namespace BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields;


use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEventInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\TypeProfileSectionFieldInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\Trans\TypeProfileSectionFieldTrans;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\TypeProfileSection;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Field\Id\TypeProfileSectionFieldUid;
use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Field\FieldEnum;
use BaksDev\Core\Type\Field\InputField;
use BaksDev\Core\Type\Locale\Locale;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use InvalidArgumentException;

/* События Field */

#[ORM\Entity]
#[ORM\Table(name: 'type_users_profile_section_field')]
class TypeProfileSectionField extends EntityEvent
{
    const TABLE = 'type_users_profile_section_field';
    
    /** ID */
    #[ORM\Id]
    #[ORM\Column(type: TypeProfileSectionFieldUid::TYPE)]
    protected TypeProfileSectionFieldUid $id;
    
    /** Связь на секцию */
    #[ORM\ManyToOne(targetEntity: TypeProfileSection::class, cascade: ['all'], inversedBy: 'field')]
    #[ORM\JoinColumn(name: 'section', referencedColumnName: 'id', nullable: true)]
    #[ORM\OrderBy(['sort' => 'ASC'])]
    protected ?TypeProfileSection $section;
    
    /** Перевод */
    #[ORM\OneToMany(mappedBy: 'field', targetEntity: TypeProfileSectionFieldTrans::class, cascade: ['all'])]
    protected Collection $translate;
    
    /** Сортировка */
    #[ORM\Column(name: 'sort', type: Types::SMALLINT, length: 3, nullable: false, options: ['default' => 500])]
    protected int $sort = 500;
    
    /** Тип поля (input, select, textarea ....)  */
    #[ORM\Column(name: 'type', type: InputField::TYPE, length: 10, nullable: false, options: ['default' => 'input'])]
    protected InputField $type;
    
    #[ORM\Column(name: 'public', type: Types::BOOLEAN, nullable: false, options: ['default' => true])]
    protected bool $public = true;
    
    #[ORM\Column(name: 'required', type: Types::BOOLEAN, nullable: false, options: ['default' => true])]
    protected bool $required = true;
    
    public function __construct(TypeProfileSection $section)
    {
        $this->id = new TypeProfileSectionFieldUid();
        $this->translate = new ArrayCollection();
        $this->section = $section;
    }
    
    /**
     * @return TypeProfileSectionFieldUid
     */
    public function getId() : TypeProfileSectionFieldUid
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
        if($dto instanceof TypeProfileSectionFieldInterface)
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
        
        if($dto instanceof TypeProfileSectionFieldInterface)
        {
            return parent::setEntity($dto);
        }
        
        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }
    
    
    public function equals($dto) : bool
    {
        if($dto instanceof TypeProfileSectionFieldInterface)
        {
            return $this->id === $dto->getEquals();
        }
        
        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }
    
    public function removeElement() : void
    {
        $this->section = null;
    }

}
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

namespace BaksDev\Users\Profile\Type\Entity\Section\Fields\Trans;

use BaksDev\Users\Profile\Type\Entity\Event\TypeProfileEventInterface;
use BaksDev\Users\Profile\Type\Entity\Section\Fields\TypeProfileSectionField;
use BaksDev\Users\Profile\Type\Entity\Section\Fields\Trans\TypeProfileSectionFieldTransInterface;
use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Locale\Locale;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use InvalidArgumentException;

/* Перевод Field */

#[ORM\Entity]
#[ORM\Table(name: 'type_users_profile_section_field_trans')]
class TypeProfileSectionFieldTrans extends EntityEvent
{
    const TABLE = 'type_users_profile_section_field_trans';
    
    /** Связь на поле */
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: TypeProfileSectionField::class, cascade: ["remove", "persist"], inversedBy: "translate")]
    #[ORM\JoinColumn(name: 'field', referencedColumnName: "id", nullable: true)]
    protected ?TypeProfileSectionField $field;
    
    /** Локаль */
    #[ORM\Id]
    #[ORM\Column(type: Locale::TYPE, length: 2)]
    protected Locale $local;
    
    /** Название */
    #[ORM\Column(type: Types::STRING, length: 100)]
    protected string $name;
    
    /** Описание */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $description;
    
    /**
     * @param TypeProfileSectionField $field
     */
    public function __construct(TypeProfileSectionField $field) { $this->field = $field; }
    
    /**
     * @param $dto
     * @return mixed
     * @throws Exception
     */
    public function getDto($dto) : mixed
    {
        if($dto instanceof TypeProfileSectionFieldTransInterface)
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
        
        if($dto instanceof TypeProfileSectionFieldTransInterface)
        {
            return parent::setEntity($dto);
        }
        
        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }
    
    
    public function equals($dto) : bool
    {
        if($dto instanceof TypeProfileSectionFieldTransInterface)
        {
            return  ($this->field->getId() === $dto->getEquals() &&
              $dto->getLocal()->getValue() === $this->local->getValue());
            
        }
        
        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }
    
}

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

namespace BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\Trans;

use BaksDev\Core\Entity\EntityState;
use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\TypeProfileSectionField;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

/* Перевод Field */


#[ORM\Entity]
#[ORM\Table(name: 'type_users_profile_section_field_trans')]
class TypeProfileSectionFieldTrans extends EntityState
{
	const TABLE = 'type_users_profile_section_field_trans';
	
	/** Связь на поле */
    #[Assert\NotBlank]
    #[Assert\Uuid]
	#[ORM\Id]
	#[ORM\ManyToOne(targetEntity: TypeProfileSectionField::class, inversedBy: "translate")]
	#[ORM\JoinColumn(name: 'field', referencedColumnName: "id", nullable: true)]
	private readonly ?TypeProfileSectionField $field;
	
	/** Локаль */
    #[Assert\NotBlank]
    #[Assert\Locale]
    #[Assert\Length(max: 2)]
	#[ORM\Id]
	#[ORM\Column(type: Locale::TYPE)]
	private readonly Locale $local;
	
	/** Название */
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
	#[ORM\Column(type: Types::STRING)]
	private string $name;
	
	/** Описание */
    #[Assert\Length(max: 255)]
	#[ORM\Column(type: Types::TEXT, nullable: true)]
	private ?string $description;
	
	
	/**
	 * @param TypeProfileSectionField $field
	 */
	public function __construct(TypeProfileSectionField $field)
    {
        $this->field = $field;
    }

    public function __toString(): string
    {
        return (string) $this->field;
    }
	
	
	public function getDto($dto): mixed
	{
        $dto = is_string($dto) && class_exists($dto) ? new $dto() : $dto;

		if($dto instanceof TypeProfileSectionFieldTransInterface)
		{
			return parent::getDto($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
	
	public function setEntity($dto): mixed
	{
		
		if($dto instanceof TypeProfileSectionFieldTransInterface || $dto instanceof self)
		{
			return parent::setEntity($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
	
	public function equals($dto) : bool
	{
		if($dto instanceof TypeProfileSectionFieldTransInterface || $dto instanceof self)
		{
			return ($this->field->getId() === $dto->getEquals() &&
				$dto->getLocal()->getLocalValue() === $this->local->getLocalValue());
			
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
}

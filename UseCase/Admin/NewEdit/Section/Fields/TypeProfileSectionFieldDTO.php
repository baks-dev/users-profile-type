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

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields;

use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\TypeProfileSectionFieldInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\Trans\TypeProfileSectionFieldTransInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Field\Id\TypeProfileSectionFieldUid;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\Trans\TypeProfileSectionFieldTransDTO;
use BaksDev\Reference\Field\Type\InputField;
use BaksDev\Core\Type\Locale\Locale;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

final class TypeProfileSectionFieldDTO implements TypeProfileSectionFieldInterface
{
	private ?TypeProfileSectionFieldUid $id = null;
	
	/** Сортировка поля в секции
	 *
	 * @var int
	 */
	#[Assert\Range(min: 0, max: 999)]
	private int $sort = 100;
	
	/** Тип поля (input, select, textarea ....) */
	#[Assert\NotBlank]
	private InputField $type;
	
	/** Публичное свойство
	 *
	 * @var bool
	 */
	private bool $public = true;
	
	/** Обязательное к заполнению
	 *
	 * @var bool
	 */
	private bool $required = true;
	
	#[Assert\Valid]
	private ArrayCollection $translate;
	
	
	public function __construct() { $this->translate = new  ArrayCollection(); }
	
	
	/**
	 * @return TypeProfileSectionFieldUid|null
	 */
	public function getEquals() : ?TypeProfileSectionFieldUid
	{
		return $this->id;
	}
	
	
	/**
	 * @param TypeProfileSectionFieldUid $id
	 */
	public function setId(TypeProfileSectionFieldUid $id) : void
	{
		$this->id = $id;
	}
	
	
	/**
	 * @return int
	 */
	public function getSort() : int
	{
		return $this->sort;
	}
	
	
	/**
	 * @param int $sort
	 */
	public function setSort(int $sort) : void
	{
		$this->sort = $sort;
	}
	
	
	/**
	 * @return InputField
	 */
	public function getType() : InputField
	{
		return $this->type;
	}
	
	
	/**
	 * @param InputField $type
	 */
	public function setType(InputField $type) : void
	{
		$this->type = $type;
	}
	
	
	/**
	 * @return bool
	 */
	public function isPublic() : bool
	{
		return $this->public;
	}
	
	
	/**
	 * @param bool $public
	 */
	public function setPublic(bool $public) : void
	{
		$this->public = $public;
	}
	
	
	/**
	 * @return bool
	 */
	public function isRequired() : bool
	{
		return $this->required;
	}
	
	
	/**
	 * @param bool $required
	 */
	public function setRequired(bool $required) : void
	{
		$this->required = $required;
	}
	
	
	/**
	 * @return ArrayCollection
	 */
	public function getTranslate() : ArrayCollection
	{
		/* Вычисляем расхождение и добавляем неопределенные локали */
		foreach(Locale::diffLocale($this->translate) as $locale)
		{
			$SectionFieldTransDTO = new TypeProfileSectionFieldTransDTO();
			$SectionFieldTransDTO->setLocal($locale);
			$this->addTranslate($SectionFieldTransDTO);
		}
		
		return $this->translate;
	}
	
	
	/**
	 * @param TypeProfileSectionFieldTransDTO $trans
	 *
	 * @return void
	 */
	public function addTranslate(TypeProfileSectionFieldTransDTO $trans) : void
	{
		
		if(!$this->translate->contains($trans))
		{
			$this->translate[] = $trans;
		}
	}
	
	
	public function removeTranslate(TypeProfileSectionFieldTransDTO $trans) : void
	{
		$this->translate->removeElement($trans);
	}
	
	
	public function getTranslateClass() : TypeProfileSectionFieldTransInterface
	{
		return new TypeProfileSectionFieldTransDTO();
	}
	
}
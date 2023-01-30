<?php
/*
 *  Copyright 2022.  Baks.dev <admin@baks.dev>
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

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields;

use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\TypeProfileSectionFieldInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Field\Id\TypeProfileSectionFieldUid;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\Trans\TransDTO;
use BaksDev\Reference\Field\Type\InputField;
use BaksDev\Core\Type\Locale\Locale;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

final class FieldDTO implements TypeProfileSectionFieldInterface
{
	/** Идентификатор для сверки коллекции  */
	private readonly ?TypeProfileSectionFieldUid $id;
	
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
	
	//    /**
	//     * @return FieldUid|null
	//     */
	//    public function getEquals() : ?FieldUid
	//    {
	//        return $this->id;
	//    }
	//
	//    /**
	//     * @param FieldUid $id
	//     */
	//    public function setId(FieldUid $id) : void
	//    {
	//        $this->id = $id;
	//    }
	//
	
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
	public function getPublic() : bool
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
	public function getRequired() : bool
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
			$SectionFieldTransDTO = new TransDTO();
			$SectionFieldTransDTO->setLocal($locale);
			$this->addTranslate($SectionFieldTransDTO);
		}
		
		return $this->translate;
	}
	
	
	/**
	 * @param TransDTO $trans
	 *
	 * @return void
	 */
	public function addTranslate(TransDTO $trans) : void
	{
		
		if(!$this->translate->contains($trans))
		{
			$this->translate[] = $trans;
		}
	}
	
	
	public function removeTranslate(TransDTO $trans) : void
	{
		$this->translate->removeElement($trans);
	}
	
}
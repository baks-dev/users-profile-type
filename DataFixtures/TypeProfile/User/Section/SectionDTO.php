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

namespace BaksDev\Users\Profile\TypeProfile\DataFixtures\TypeProfile\User\Section;

use BaksDev\Users\Profile\TypeProfile\Entity\Section\TypeProfileSectionInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Id\TypeProfileSectionUid;

use BaksDev\Reference\Field\Type\FieldEnum;
use BaksDev\Reference\Field\Type\InputField;
use BaksDev\Core\Type\Locale\Locale;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

final class SectionDTO implements TypeProfileSectionInterface
{
	
	private readonly ?TypeProfileSectionUid $id;
	
	/** Сортировка секции свойств продукта категории */
	#[Assert\NotBlank]
	#[Assert\Range(min: 0, max: 999)]
	private int $sort;
	
	/** Настройки локали секции */
	#[Assert\Valid]
	private ArrayCollection $translate;
	
	/** Коллекция свойств продукта в секции */
	#[Assert\Valid]
	private ArrayCollection $field;
	
	
	public function __construct()
	{
		$this->translate = new ArrayCollection();
		$this->field = new ArrayCollection();
	}
	
	//    /**
	//     * @return TypeProfileSectionUid|null
	//     */
	//    public function getEquals() : ?TypeProfileSectionUid
	//    {
	//        return $this->id;
	//    }
	
	//    /**
	//     * @param TypeProfileSectionUid $id
	//     */
	//    public function setId(TypeProfileSectionUid $id) : void
	//    {
	//        $this->id = $id;
	//    }
	
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
		/* Перевод секций */
		foreach(Locale::diffLocale($this->translate) as $locale)
		{
			$TransDTO = new Trans\TransDTO($sort);
			$TransDTO->setLocal($locale);
			$this->addTranslate($TransDTO);
		}
		
		/* Поля секций */
		foreach(Fields\Trans\TransDTO::NAME[$sort] as $key => $value)
		{
			$FieldDTO = new Fields\FieldDTO($sort);
			$FieldDTO->setSort($key);
			$this->addField($FieldDTO);
		}
		
		$this->sort = $sort;
	}
	
	
	public function getTranslate() : ArrayCollection
	{
		return $this->translate;
	}
	
	
	public function addTranslate(Trans\TransDTO $trans) : void
	{
		if(!$this->translate->contains($trans))
		{
			$this->translate[] = $trans;
		}
		
	}
	
	
	/**
	 * @return ArrayCollection
	 */
	public function getField() : ArrayCollection
	{
		return $this->field;
	}
	
	
	public function addField(Fields\FieldDTO $field) : void
	{
		if(!$this->field->contains($field))
		{
			$this->field[] = $field;
		}
	}
	
}
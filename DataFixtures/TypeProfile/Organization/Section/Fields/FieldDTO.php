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

namespace BaksDev\Users\Profile\TypeProfile\DataFixtures\TypeProfile\Organization\Section\Fields;

use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\TypeProfileSectionFieldInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Field\Id\TypeProfileSectionFieldUid;

use BaksDev\Reference\Field\Type\FieldEnum;
use BaksDev\Reference\Field\Type\InputField;
use BaksDev\Core\Type\Locale\Locale;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

final class FieldDTO implements TypeProfileSectionFieldInterface
{
	

	/** Идентификатор для сверки коллекции  */
	private readonly ?TypeProfileSectionFieldUid $id;
	
	/** Сортировка поля в секции */
	#[Assert\Range(min: 0, max: 999)]
	private readonly int $sort;
	
	/** Тип поля (input, select, textarea ....) */
	#[Assert\NotBlank]
	private readonly InputField $type;
	
	/** Публичное свойство */
	private readonly bool $public;
	
	/** Обязательное к заполнению */
	private readonly bool $required;
	
	#[Assert\Valid]
	private readonly ArrayCollection $translate;
	
	
	/* Вспомогательное свойство */
	private int $key;
	
	
	public function __construct(int $key)
	{
		$this->translate = new  ArrayCollection();
		$this->key = $key;
	}
	
	public function getSort() : int
	{
		return $this->sort;
	}
	
	public function setSort(int $sort) : void
	{
		$this->public = true;
		$this->required = true;
		$this->type = new InputField(FieldEnum::INPUT);
		$this->sort = $sort;
		
		/* Вычисляем расхождение и добавляем неопределенные локали */
		foreach(Locale::diffLocale($this->translate) as $locale)
		{
			$SectionFieldTransDTO = new Trans\TransDTO($this->key, $this->sort);
			$SectionFieldTransDTO->setLocal($locale);

			$this->addTranslate($SectionFieldTransDTO);
		}
		
		
		
	}
	
	/**
	 * @return InputField
	 */
	public function getType() : InputField
	{
		return $this->type;
	}
	
	public function getPublic() : bool
	{
		return $this->public;
	}
	
	public function getRequired() : bool
	{
		return $this->required;
	}
	
	
	public function getTranslate() : ArrayCollection
	{
		return $this->translate;
	}
	
	public function addTranslate(Trans\TransDTO $trans) : void
	{
		$this->translate[] = $trans;
		
	}
	
}
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

use BaksDev\Core\Type\Field\InputField;
use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\TypeProfileSectionFieldInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Field\Id\TypeProfileSectionFieldUid;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\Trans\SectionFieldTransDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/** @see TypeProfileSectionField */
final class SectionFieldDTO implements TypeProfileSectionFieldInterface
{
	/** Идентификатор для сверки коллекции  */
	private readonly ?TypeProfileSectionFieldUid $id;

	/** Сортировка поля в секции  */
	#[Assert\Range(min: 0, max: 999)]
	private int $sort = 100;
	
	/** Тип поля (input, select, textarea ....) */
	#[Assert\NotBlank]
	private InputField $type;
	
	/** Публичное свойство */
	private bool $public = true;
	
	/** Присутствует в карточке */
	private bool $card = true;
	
	/** Обязательное к заполнению */
	private bool $required = true;
	
	#[Assert\Valid]
	private ArrayCollection $translate;
	
	
	public function __construct() { $this->translate = new  ArrayCollection(); }


//    /** Для теста */
//    public function newSectionField(): void
//    {
//        $this->id = clone new TypeProfileSectionFieldUid();
//    }

	
	/** Сортировка поля в секции  */
	
	public function getSort() : int
	{
		return $this->sort;
	}
	
	
	public function setSort(int $sort) : void
	{
		
		$this->sort = $sort;
	}
	
	
	/** Тип поля (input, select, textarea ....) */
	
	public function getType() : InputField
	{
		return $this->type;
	}
	
	
	public function setType(InputField $type) : void
	{
		$this->type = $type;
	}
	
	
	/** Публичное свойство */
	
	public function getPublic() : bool
	{
		return $this->public;
	}
	
	
	public function setPublic(bool $public) : void
	{
		$this->public = $public;
	}
	
	
	/** Присутствует в карточке */
	
	public function getCard() : bool
	{
		return $this->card;
	}
	
	
	public function setCard(bool $card) : void
	{
		$this->card = $card;
	}
	
	
	/** Обязательное к заполнению */
	
	public function getRequired() : bool
	{
		return $this->required;
	}
	
	
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
			$SectionFieldTransDTO = new SectionFieldTransDTO();
			$SectionFieldTransDTO->setLocal($locale);
			$this->addTranslate($SectionFieldTransDTO);
		}
		
		return $this->translate;
	}
	
	
	/**
	 * @param SectionFieldTransDTO $trans
	 *
	 * @return void
	 */
	public function addTranslate(SectionFieldTransDTO $trans) : void
	{
        if(empty($trans->getLocal()->getLocalValue()))
        {
            return;
        }

		if(!$this->translate->contains($trans))
		{
			$this->translate[] = $trans;
		}
	}
	
	
	public function removeTranslate(SectionFieldTransDTO $trans) : void
	{
		$this->translate->removeElement($trans);
	}
	
}
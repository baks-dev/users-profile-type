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

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit;

use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEventInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Event\TypeProfileEventUid;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\SectionDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Trans\TransDTO;
use BaksDev\Core\Type\Locale\Locale;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

final class TypeProfileDTO implements TypeProfileEventInterface
{
	/** Идентификатор события */
	#[Assert\Uuid]
	private ?TypeProfileEventUid $id = null;
	
	/** Настройки локали */
	#[Assert\Valid]
	private ArrayCollection $translate;
	
	/** Секции свойств */
	#[Assert\Valid]
	private ArrayCollection $section;
	
	/** Сортировка */
	#[Assert\Range(min: 1)]
	private int $sort = 500;
	
	
	public function __construct()
	{
		$this->translate = new ArrayCollection();
		$this->section = new ArrayCollection();
	}
	
	
	/** Идентификатор события */
	public function setId(TypeProfileEventUid $id) : void
	{
		$this->id = $id;
	}
	
	
	public function getEvent() : ?TypeProfileEventUid
	{
		return $this->id;
	}
	
	
	/** Настройки локали */
	
	public function getTranslate() : ArrayCollection
	{
		/* Вычисляем расхождение и добавляем неопределенные локали */
		foreach(Locale::diffLocale($this->translate) as $locale)
		{
			$TransFormDTO = new TransDTO();
			$TransFormDTO->setLocal($locale);
			$this->addTranslate($TransFormDTO);
		}
		
		return $this->translate;
	}
	
	
	public function addTranslate(TransDTO $trans) : void
	{
		if(!$this->translate->contains($trans))
		{
			$this->translate->add($trans);
		}
	}
	
	
	public function removeTranslate(TransDTO $trans) : void
	{
		$this->translate->removeElement($trans);
	}
	
	
	/** Секции свойств */
	
	public function getSection() : ArrayCollection
	{
		if($this->section->isEmpty())
		{
			$SectionDTO = new SectionDTO();
			$this->addSection($SectionDTO);
		}
		
		return $this->section;
	}
	
	
	public function addSection(SectionDTO $section) : void
	{
		if(!$this->section->contains($section))
		{
			$this->section->add($section);
		}
	}
	
	
	public function removeSection(SectionDTO $section) : void
	{
		$this->section->removeElement($section);
	}
	
	
	/** Сортировка  */
	
	public function getSort() : int
	{
		return $this->sort;
	}
	
	
	public function setSort(int $sort) : void
	{
		$this->sort = $sort;
	}
	
}
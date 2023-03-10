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

namespace BaksDev\Users\Profile\TypeProfile\DataFixtures\TypeProfile\Organization\Section\Trans;

use BaksDev\Users\Profile\TypeProfile\Entity\Section\Section;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Trans\TransInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Trans\TypeProfileSectionTransInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\TypeProfileSection;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Id\SectionUid;
use BaksDev\Core\Type\Locale\Locale;
use Symfony\Component\Validator\Constraints as Assert;

final class TransDTO implements TypeProfileSectionTransInterface
{
	
	public const NAME = [
		100 =>
			[
				'ru' => 'Контактные данные',
				'en' => 'Contact details',
			],
		
		200 =>
			[
				'ru' => 'Официальные реквизиты',
				'en' => 'Official details',
			],
	];
	
	public const DESC = [
		100 =>
			[
				'ru' => 'Данные для связи менеджерами магазина с покупателем.',
				'en' => 'Data for communication between store managers and the buyer.',
			],
		
		200 =>
			[
				'ru' => 'Официальные реквизиты',
				'en' => 'Реквизиты для оформления документов',
			],
	];
	
	private readonly ?TypeProfileSection $section;
	
	private readonly Locale $local;
	
	/** Название раздела (строка с точкой, нижнее подчеркивание тире процент скобки) */
	#[Assert\NotBlank]
	#[Assert\Regex(pattern: '/^[\w \.\,\_\-\(\)\%]+$/iu')]
	private readonly string $name;
	
	/** Краткое описание */
	#[Assert\Regex(pattern: '/^[\w \.\,\_\-\(\)\%]+$/iu')]
	private readonly string $description;
	
	private int $key;
	
	
	public function __construct(int $key)
	{
		$this->key = $key;
	}
	
	
	public function getLocal() : Locale
	{
		return $this->local;
	}
	
	
	public function setLocal(string $local) : void
	{
		$this->name = self::NAME[$this->key][$local];
		$this->description = self::DESC[$this->key][$local];
		$this->local = new Locale($local);
	}
	
	
	/**
	 * @return string|null
	 */
	public function getName() : ?string
	{
		return $this->name;
	}
	
	
	public function getDescription() : ?string
	{
		return $this->description;
	}
	
}
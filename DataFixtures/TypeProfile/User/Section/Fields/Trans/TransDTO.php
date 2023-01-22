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

namespace BaksDev\Users\Profile\TypeProfile\DataFixtures\TypeProfile\User\Section\Fields\Trans;

use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\Field;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\Trans\TransInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\Trans\TypeProfileSectionFieldTransInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\TypeProfileSectionField;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Field\Id\FieldUid;
use BaksDev\Core\Type\Locale\Locale;
use Symfony\Component\Validator\Constraints as Assert;

final class TransDTO implements TypeProfileSectionFieldTransInterface
{
	public const NAME = [
		100 =>
			[
				100 =>
					['ru' => 'Контактное лицо',
					 'en' => 'The contact person',
					],
				110 =>
					['ru' => 'Контактный телефон',
					 'en' => 'Contact number',
					],
				120 =>
					['ru' => 'Контактный E-mail',
					 'en' => 'Contact E-mail',
					],
			],
	];
	
	public const DESC = [
		100 =>
			[

				100 =>
					['ru' => 'ФИО пользователя',
					 'en' => 'Name of person',
					],
				110 =>
					['ru' => 'Контактный телефон для связи',
					 'en' => 'Contact number for communication',
					],
				120 =>
					['ru' => 'Контактный E-mail для связи',
					 'en' => 'Contact E-mail for communication',
					],
			],
		
		
	];
	
	private readonly ?TypeProfileSectionField $field;
	
	/**
	 * @var Locale
	 */
	private readonly Locale $local;
	
	/** Название раздела (строка с точкой, нижнее подчеркивание тире процент скобки) */
	#[Assert\NotBlank]
	#[Assert\Regex(pattern: '/^[\w \+\.\,\_\-\(\)\%]+$/iu')]
	private readonly string $name;
	
	/** Краткое описание */
	#[Assert\Regex(pattern: '/^[\w \+\.\,\_\-\(\)\%]+$/iu')]
	private readonly string $description;
	
	private readonly int $key;
	private readonly int $sort;
	
	public function __construct(int $key, int $sort){
		$this->key = $key;
		$this->sort = $sort;
	}
	
	/**
	 * @return Locale
	 */
	public function getLocal() : Locale
	{
		return $this->local;
	}
	
	/**
	 * @param string|Locale $local
	 */
	public function setLocal(string $local) : void
	{
		$this->name = self::NAME[$this->key][$this->sort][(string)$local];
		$this->description = self::DESC[$this->key][$this->sort][(string)$local];
		$this->local = new Locale($local);
	}
	
	/**
	 * @return string|null
	 */
	public function getName() : ?string
	{
		return $this->name;
	}
	

	
	/**
	 * @return string|null
	 */
	public function getDescription() : ?string
	{
		return $this->description;
	}
	
}
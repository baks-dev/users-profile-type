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

namespace BaksDev\Users\Profile\TypeProfile\DataFixtures\TypeProfile\Organization\Section\Fields\Trans;

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
					['ru' => 'Название организации',
					 'en' => 'Name of the organization',
					],
				110 =>
					['ru' => 'Юр. адрес',
					 'en' => 'Legal address',
					],
				120 =>
					['ru' => 'Контактное лицо',
					 'en' => 'The contact person',
					],
				130 =>
					['ru' => 'Контактный телефон',
					 'en' => 'Contact number',
					],
				140 =>
					['ru' => 'Контактный E-mail',
					 'en' => 'Contact E-mail',
					],
			],
		
		
		200 =>
			[
				100 =>
					['ru' => 'ИНН',
					 'en' => 'INN',
					],
				110 =>
					['ru' => 'BIK банка',
					 'en' => 'Bank BIK',
					],
				120 =>
					['ru' => 'Расчетный счет',
					 'en' => 'Checking account',
					],
				130 =>
					['ru' => 'Корреспондентский счёт',
					 'en' => 'Correspondent account',
					],
			
			],
	];
	
	public const DESC = [
		100 =>
			[
				100 =>
					['ru' => 'Название организации, предприятия или учреждения, содержащее указание на его ОПФ и отражающее характер его деятельности',
					 'en' => 'The name of the organization, enterprise or institution, containing an indication of its OPF and reflecting the nature of its activities',
					],
				110 =>
					['ru' => 'Местоположение организации, предприятия, компании и любого другого юридического лица',
					 'en' => 'Location of the organization, enterprise, company and any other legal entity',
					],
				120 =>
					['ru' => 'ФИО ответственного человека',
					 'en' => 'Name of responsible person',
					],
				130 =>
					['ru' => 'Контактный телефон для связи',
					 'en' => 'Contact number  for communication',
					],
				140 =>
					['ru' => 'Контактный E-mail для связи',
					 'en' => 'Contact E-mail for communication',
					],
			],
		
		
		200 =>
			[
				100 =>
					['ru' => 'Идентификационный номер налогоплательщика',
					 'en' => 'Taxpayer identification number',
					],
				110 =>
					['ru' => 'Банковский идентификационный код (БИК) - девятизначный уникальный код кредитной организации.',
					 'en' => 'Bank Identification Code (BIC) - a nine-digit unique code of a credit institution.',
					],
				120 =>
					['ru' => 'Банковский счет, предназначенный для хранения денежных средств и проведения приходно-расходных операций',
					 'en' => 'A bank account designed to store funds and conduct income and expenditure transactions',
					],
				130 =>
					['ru' => 'Счет для отражения расчётов, производимых одной банковской организацией по поручению и за счёт другой на основании заключённого между ними корреспондентского соглашения',
					 'en' => 'An account for reflecting settlements made by one banking organization on behalf of and at the expense of another on the basis of a correspondent agreement concluded between them',
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
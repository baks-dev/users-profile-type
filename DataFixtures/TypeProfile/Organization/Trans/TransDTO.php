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

namespace BaksDev\Users\Profile\TypeProfile\DataFixtures\TypeProfile\Organization\Trans;

use BaksDev\Users\Profile\TypeProfile\Entity\Event\Event;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Entity\Trans\TransInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Trans\TypeProfileTransInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Event\ProfileEvent;
use BaksDev\Core\Type\Locale\Locale;
use Symfony\Component\Validator\Constraints as Assert;

final class TransDTO implements TypeProfileTransInterface
{
	
	public const NAME = [
		'ru' => 'Юр. лицо',
		'en' => 'Organization'
	];
	
	public const DESC = [
		'ru' => 'Пользователь с возможностью оплаты безналичным расчетом ',
		'en' => 'User with the option to pay by bank transfer'
	];

	/** Локаль  */
    private readonly Locale $local;
    
    /** Название раздела (строка с точкой, нижнее подчеркивание тире процент скобки) */
    #[Assert\NotBlank]
	#[Assert\Regex(pattern: '/^[\w \.\,\_\-\(\)\%]+$/iu')]
    private readonly string $name;
    
    /** Краткое описание */
	#[Assert\Regex(pattern: '/^[\w \.\,\_\-\(\)\%]+$/iu')]
    private readonly string $description;
    

	public function setLocal(string $local) : void
	{
		$this->local = new Locale($local);
		$this->name = self::NAME[$local];
		$this->description = self::DESC[$local];
	}

	
    public function getLocal() : Locale
    {
        return $this->local;
    }
	
    public function getName() : ?string
    {
        return $this->name;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

}
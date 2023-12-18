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

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Trans;

use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Section;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Trans\TransInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Trans\TypeProfileSectionTransInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\TypeProfileSection;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Id\SectionUid;
use ReflectionProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class SectionTransDTO implements TypeProfileSectionTransInterface
{
	//private ?TypeProfileSection $section;
	
	private readonly Locale $local;
	
	/** Название раздела (строка с точкой, нижнее подчеркивание тире процент скобки) */
	#[Assert\NotBlank]
	#[Assert\Regex(pattern: '/^[\w \.\,\_\-\(\)\%]+$/iu')]
	private ?string $name;
	
	/** Краткое описание */
	#[Assert\Regex(pattern: '/^[\w \.\,\_\-\(\)\%]+$/iu')]
	private ?string $description = null;

//    /**
//     * Section
//     */
//    public function getSection(): ?TypeProfileSection
//    {
//        return $this->section;
//    }


	public function getLocal() : Locale
	{
		return $this->local;
	}


    public function setLocal(Locale|string $local) : void
    {
        if(!(new ReflectionProperty(self::class, 'local'))->isInitialized($this))
        {
            $this->local = $local instanceof Locale ? $local : new Locale($local);
        }
    }
	
	
	public function getName() : ?string
	{
		return $this->name;
	}
	
	
	public function setName(?string $name) : void
	{
		$this->name = $name;
	}
	
	
	public function getDescription() : ?string
	{
		return $this->description;
	}
	
	
	public function setDescription(?string $description) : void
	{
		$this->description = $description;
	}
	
}
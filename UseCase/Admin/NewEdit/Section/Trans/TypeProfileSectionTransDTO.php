<?php
/*
 *  Copyright 2023.  Baks.dev <admin@baks.dev>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 *
 *
 */

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Trans;


use BaksDev\Users\Profile\TypeProfile\Entity\Section\TypeProfileSection;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Trans\TypeProfileSectionTransInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Id\TypeProfileSectionUid;
use BaksDev\Core\Type\Locale\Locale;
use Symfony\Component\Validator\Constraints as Assert;

final class TypeProfileSectionTransDTO implements TypeProfileSectionTransInterface
{
	private readonly ?TypeProfileSection $section;
	
	private readonly Locale $local;
	
	/** Название раздела (строка с точкой, нижнее подчеркивание тире процент скобки) */
	#[Assert\NotBlank]
	#[Assert\Regex(pattern: '/^[\w \.\,\_\-\(\)\%]+$/iu')]
	private ?string $name;
	
	/** Краткое описание */
	#[Assert\Regex(pattern: '/^[\w \.\,\_\-\(\)\%]+$/iu')]
	private ?string $description = null;
	
	
	public function getLocal() : Locale
	{
		return $this->local;
	}
	
	public function setLocal(string $local) : void
	{
		if(!(new \ReflectionProperty($this::class, 'local'))->isInitialized($this))
		{
			$this->local = new Locale($local) ;
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
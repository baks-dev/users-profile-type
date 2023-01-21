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

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\Trans;

use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\TypeProfileSectionField;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\Trans\TypeProfileSectionFieldTransInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Field\Id\TypeProfileSectionFieldUid;
use BaksDev\Core\Type\Locale\Locale;
use Symfony\Component\Validator\Constraints as Assert;

final class TypeProfileSectionFieldTransDTO implements TypeProfileSectionFieldTransInterface
{
    
    //private ?TypeProfileSectionFieldUid $field = null;
    
    /**
     * @var Locale
     */
    private Locale $local;
    
    /** Название раздела (строка с точкой, нижнее подчеркивание тире процент скобки) */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[\w \+\.\_\-\(\)\%]+$/iu')]
    private ?string $name;
    
    /** Краткое описание */
    #[Assert\Regex(pattern: '/^[\w \+\.\_\-\(\)\%]+$/iu')]
    private ?string $description = null;
    
    /**
     * @param TypeProfileSectionFieldUid|null $field
     */
    public function setField(?TypeProfileSectionField $field) : void
    {
        $this->field = $field?->getId();
    }
    
    /**
     * @return TypeProfileSectionFieldUid|null
     */
    public function getEquals() : ?TypeProfileSectionFieldUid
    {
        return $this->field;
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
    public function setLocal(string|Locale $local) : void
    {
        $this->local = $local instanceof Locale ? $local : new Locale($local) ;
    }
    
    /**
     * @return string|null
     */
    public function getName() : ?string
    {
        return $this->name;
    }
    
    /**
     * @param string|null $name
     */
    public function setName(?string $name) : void
    {
        $this->name = $name;
    }
    
    /**
     * @return string|null
     */
    public function getDescription() : ?string
    {
        return $this->description;
    }
    
    /**
     * @param string|null $description
     */
    public function setDescription(?string $description) : void
    {
        $this->description = $description;
    }
}
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

namespace BaksDev\Users\Profile\Type\UseCase\Admin\NewEdit\Section;

use BaksDev\Users\Profile\Type\Entity\Section\Fields\TypeProfileSectionFieldInterface;
use BaksDev\Users\Profile\Type\Entity\Section\TypeProfileSectionInterface;
use BaksDev\Users\Profile\Type\Entity\Section\Trans\TypeProfileSectionTransInterface;
use BaksDev\Users\Profile\Type\Type\Section\Id\TypeProfileSectionUid;
use BaksDev\Users\Profile\Type\UseCase\Admin\NewEdit\Section\Fields\TypeProfileSectionFieldDTO;
use BaksDev\Users\Profile\Type\UseCase\Admin\NewEdit\Section\Trans\TypeProfileSectionTransDTO;
use BaksDev\Core\Type\Locale\Locale;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

final class TypeProfileSectionDTO implements TypeProfileSectionInterface
{
    private ?TypeProfileSectionUid $id = null;
    
    /** Сортировка секции свойств продукта категории
     * @var int
     */
    #[Assert\NotBlank]
    #[Assert\Range(min: 0, max: 999)]
    private int $sort = 100;
    
    /** Настройки локали секции */
    #[Assert\Valid]
    private ArrayCollection $translate;
    
    /** Коллекция свойств продукта в секции */
    #[Assert\Valid]
    private ArrayCollection $field;
    
    public function __construct()
    {
        $this->translate = new ArrayCollection();
        $this->field = new ArrayCollection();
    }
    
    /**
     * @return TypeProfileSectionUid|null
     */
    public function getEquals() : ?TypeProfileSectionUid
    {
        return $this->id;
    }
    
    /**
     * @param TypeProfileSectionUid $id
     */
    public function setId(TypeProfileSectionUid $id) : void
    {
        $this->id = $id;
    }
    
    
    /**
     * @return int
     */
    public function getSort() : int
    {
        return $this->sort;
    }
    
    /**
     * @param int $sort
     */
    public function setSort(int $sort) : void
    {
        $this->sort = $sort;
    }
    

    
    /**
     * @return ArrayCollection
     */
    public function getTranslate() : ArrayCollection
    {
        /* Вычисляем расхождение и добавляем неопределенные локали */
        foreach(Locale::diffLocale($this->translate) as $locale)
        {
            $TransDTO = new TypeProfileSectionTransDTO();
            $TransDTO->setLocal($locale);
            $this->addTranslate($TransDTO);
        }
        
        return $this->translate;
    }
    
    
    /** Добавляем перевод категории
     *
     * @param TypeProfileSectionTransDTO $trans
     *
     * @return void
     */
    public function addTranslate(TypeProfileSectionTransDTO $trans) : void
    {
        if(!$this->translate->contains($trans))
        {
            $this->translate[] = $trans;
        }
    }
    
    public function removeTranslate(TypeProfileSectionTransDTO $trans) : void
    {
        $this->translate->removeElement($trans);
    }
    
    public function getTranslateClass() : TypeProfileSectionTransInterface
    {
		return new TypeProfileSectionTransDTO();
    }
    
    
    /**
     * @return ArrayCollection
     */
    public function getField() : ArrayCollection
    {
        if($this->field->isEmpty())
        {
            $FieldDTO = new TypeProfileSectionFieldDTO();
            $this->addField($FieldDTO);
        }
        
        
        return $this->field;
    }
    
    /**
     * @param ArrayCollection $fields
     */
    public function addField(TypeProfileSectionFieldDTO $field) : void
    {
        if(!$this->field->contains($field))
        {
            $this->field[] = $field;
        }
    }
    
    public function removeField(TypeProfileSectionFieldDTO $field) : void
    {
        $this->field->removeElement($field);
    }
    
    public function getFieldClass() : TypeProfileSectionFieldInterface
    {
        return new TypeProfileSectionFieldDTO();
    }
    

    
}
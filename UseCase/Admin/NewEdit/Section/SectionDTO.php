<?php
/*
 *  Copyright 2024.  Baks.dev <admin@baks.dev>
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
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section;

use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\FieldInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\SectionInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Trans\TransInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\TypeProfileSectionInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Id\SectionUid;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\SectionFieldDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Trans\SectionTransDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

final class SectionDTO implements TypeProfileSectionInterface
{
    //private ?TypeProfileSectionUid $id;

    /** Сортировка секции свойств продукта категории
     *
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

    //    /**
    //     * Id
    //     */
    //    public function getId(): ?TypeProfileSectionUid
    //    {
    //        return $this->id;
    //    }


    //    /**
    //     * @return TypeProfileSectionUid|null
    //     */
    //    public function getEquals() : ?TypeProfileSectionUid
    //    {
    //        return $this->id;
    //    }

    //    /**
    //     * @param TypeProfileSectionUid $id
    //     */
    //    public function setId(TypeProfileSectionUid $id) : void
    //    {
    //        $this->id = $id;
    //    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }


    /**
     * @param int $sort
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }


    /**
     * @return ArrayCollection
     */
    public function getTranslate(): ArrayCollection
    {
        /* Вычисляем расхождение и добавляем неопределенные локали */
        foreach(Locale::diffLocale($this->translate) as $locale)
        {
            $TransDTO = new SectionTransDTO();
            $TransDTO->setLocal($locale);
            $this->addTranslate($TransDTO);
        }

        return $this->translate;
    }

    public function addTranslate(SectionTransDTO $trans): void
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


    public function removeTranslate(SectionTransDTO $trans): void
    {
        $this->translate->removeElement($trans);
    }


    /**
     * @return ArrayCollection
     */
    public function getField(): ArrayCollection
    {
        if($this->field->isEmpty())
        {
            $FieldDTO = new SectionFieldDTO();
            $this->addField($FieldDTO);
        }

        return $this->field;
    }


    public function addField(SectionFieldDTO $field): void
    {
        if(!$this->field->contains($field))
        {
            $this->field[] = $field;
        }
    }


    public function removeField(SectionFieldDTO $field): void
    {
        $this->field->removeElement($field);
    }

}

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

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section;

use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Fields\FieldInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\SectionInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\Trans\TransInterface;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\TypeProfileSectionInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Id\SectionUid;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Id\TypeProfileSectionUid;
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

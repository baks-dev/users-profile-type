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

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit;

use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEventInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Event\TypeProfileEventUid;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\SectionDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Trans\TransDTO;
use Doctrine\Common\Collections\ArrayCollection;
use ReflectionProperty;
use Symfony\Component\Validator\Constraints as Assert;

/** @see TypeProfileEvent */
final class TypeProfileDTO implements TypeProfileEventInterface
{
    /** Идентификатор события */
    #[Assert\Uuid]
    private ?TypeProfileEventUid $id = null;


    /** ID Profile */
    private readonly TypeProfileUid $profile;

    /** Настройки локали */
    #[Assert\Valid]
    private ArrayCollection $translate;

    /** Секции свойств */
    #[Assert\Valid]
    private ArrayCollection $section;

    /** Сортировка */
    #[Assert\Range(min: 1)]
    private int $sort = 500;

    /** Информация о типе профиля */
    #[Assert\Valid]
    private Info\TypeProfileInfoDTO $info;


    public function __construct()
    {
        $this->translate = new ArrayCollection();
        $this->section = new ArrayCollection();
        $this->info = new Info\TypeProfileInfoDTO();
    }


    /** Идентификатор события */
    public function setId(TypeProfileEventUid $id): void
    {
        $this->id = $id;
    }


    public function getEvent(): ?TypeProfileEventUid
    {
        return $this->id;
    }


    /** Настройки локали */

    public function getTranslate(): ArrayCollection
    {
        /* Вычисляем расхождение и добавляем неопределенные локали */
        foreach(Locale::diffLocale($this->translate) as $locale)
        {
            $TransFormDTO = new TransDTO();
            $TransFormDTO->setLocal($locale);
            $this->addTranslate($TransFormDTO);
        }

        return $this->translate;
    }


    public function addTranslate(TransDTO $trans): void
    {
        if(empty($trans->getLocal()->getLocalValue()))
        {
            return;
        }

        if(!$this->translate->contains($trans))
        {
            $this->translate->add($trans);
        }
    }


    public function removeTranslate(TransDTO $trans): void
    {
        $this->translate->removeElement($trans);
    }


    /** Секции свойств */

    public function getSection(): ArrayCollection
    {
        return $this->section;
    }

    public function addSection(SectionDTO $section): void
    {
        if(!$this->section->contains($section))
        {
            $this->section->add($section);
        }
    }

    public function removeSection(SectionDTO $section): void
    {
        $this->section->removeElement($section);
    }


    /** Сортировка  */

    public function getSort(): int
    {
        return $this->sort;
    }


    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * Profile
     */
    public function getProfile(): ?TypeProfileUid
    {
        return $this->profile;
    }

    public function setProfile(TypeProfileUid $profile): self
    {
        if(!(new ReflectionProperty(self::class, 'profile'))->isInitialized($this))
        {
            $this->profile = $profile;
        }

        return $this;
    }

    /**
     * Info
     */
    public function getInfo(): Info\TypeProfileInfoDTO
    {
        return $this->info;
    }

    public function setInfo(Info\TypeProfileInfoDTO $info): self
    {
        $this->info = $info;
        return $this;
    }

}

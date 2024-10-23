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

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Trans;

use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Entity\Trans\TypeProfileTransInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Event\TypeProfileEventUid;
use Symfony\Component\Validator\Constraints as Assert;

final class TypeProfileTransDTO implements TypeProfileTransInterface
{
    //    /**
    //     * Идентификатор события
    //     *
    //     * @var TypeProfileEventUid|null
    //     */
    //    private ?TypeProfileEventUid $event = null;

    /** Идентификатор события */
    private readonly TypeProfileEvent $event;

    /**
     * @var Locale
     */
    private readonly Locale $local;

    /** Название раздела (строка с точкой, нижнее подчеркивание тире процент скобки) */
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[\w \.\_\-\(\)\%]+$/iu')]
    private ?string $name;

    /** Краткое описание */
    #[Assert\Regex(pattern: '/^[\w \.\_\-\(\)\%]+$/iu')]
    private ?string $description = null;


    /**
     * @return TypeProfileEventUid
     */
    public function getEquals(): ?TypeProfileEventUid
    {
        return $this->event;
    }


    /**
     * @param TypeProfileEvent $event
     */
    public function setEvent(TypeProfileEvent $event): void
    {
        $this->event = $event->getId();
    }


    /**
     * @return Locale
     */
    public function getLocal(): Locale
    {
        return $this->local;
    }


    /**
     * @param string|Locale $local
     */
    public function setLocal(string|Locale $local): void
    {
        $this->local = $local instanceof Locale ? $local : new Locale($local);
    }


    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }


    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

}

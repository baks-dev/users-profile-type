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

declare(strict_types=1);

namespace BaksDev\Users\Profile\TypeProfile\Listeners\Event;

use BaksDev\Users\Profile\TypeProfile\Type\Id\Choice\Collection\TypeProfileCollection;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileType;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

#[AsEventListener(event: ControllerEvent::class)]
#[AsEventListener(event: ConsoleEvents::COMMAND)]
final class TypeProfileListener
{
    private TypeProfileCollection $collection;

    public function __construct(TypeProfileCollection $collection)
    {
        $this->collection = $collection;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        // Инициируем статусы заказов
        if(in_array(TypeProfileType::class, get_declared_classes(), true))
        {
            $this->collection->cases();
        }
    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        // Всегда инициируем в консольной комманде
        $this->collection->cases();
    }

}

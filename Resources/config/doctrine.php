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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use BaksDev\Users\Profile\TypeProfile\BaksDevUsersProfileTypeProfileBundle;
use BaksDev\Users\Profile\TypeProfile\Type\Event\TypeProfileEventType;
use BaksDev\Users\Profile\TypeProfile\Type\Event\TypeProfileEventUid;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileType;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Field\Id\TypeProfileSectionFieldType;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Field\Id\TypeProfileSectionFieldUid;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Id\TypeProfileSectionType;
use BaksDev\Users\Profile\TypeProfile\Type\Section\Id\TypeProfileSectionUid;
use BaksDev\Users\Profile\TypeProfile\Type\Settings\TypeProfileSettingsIdentifier;
use BaksDev\Users\Profile\TypeProfile\Type\Settings\TypeProfileSettingsType;
use Symfony\Config\DoctrineConfig;

return static function(ContainerConfigurator $container, DoctrineConfig $doctrine) {

    $doctrine->dbal()->type(TypeProfileUid::TYPE)->class(TypeProfileType::class);
    $doctrine->dbal()->type(TypeProfileEventUid::TYPE)->class(TypeProfileEventType::class);
    $doctrine->dbal()->type(TypeProfileSettingsIdentifier::TYPE)->class(TypeProfileSettingsType::class);
    $doctrine->dbal()->type(TypeProfileSectionUid::TYPE)->class(TypeProfileSectionType::class);
    $doctrine->dbal()->type(TypeProfileSectionFieldUid::TYPE)->class(TypeProfileSectionFieldType::class);

    $emDefault = $doctrine->orm()->entityManager('default')->autoMapping(true);

    $emDefault->mapping('TypeProfile')
        ->type('attribute')
        ->dir(BaksDevUsersProfileTypeProfileBundle::PATH.'Entity')
        ->isBundle(false)
        ->prefix(BaksDevUsersProfileTypeProfileBundle::NAMESPACE.'\\Entity')
        ->alias('TypeProfile');
};

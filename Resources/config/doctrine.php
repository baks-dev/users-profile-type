<?php

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

return static function (ContainerConfigurator $container, DoctrineConfig $doctrine) {

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

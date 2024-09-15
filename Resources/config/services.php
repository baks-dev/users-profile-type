<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use BaksDev\Users\Profile\TypeProfile\BaksDevUsersProfileTypeProfileBundle;
use BaksDev\Users\Profile\TypeProfile\Type\Id\Choice\Collection\TypeProfileInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Id\Choice\TypeProfileOrganization;
use BaksDev\Users\Profile\TypeProfile\Type\Id\Choice\TypeProfileUser;

return static function (ContainerConfigurator $configurator) {

    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $NAMESPACE = BaksDevUsersProfileTypeProfileBundle::NAMESPACE;
    $PATH = BaksDevUsersProfileTypeProfileBundle::PATH;

    $services->load($NAMESPACE, $PATH)
        ->exclude([
            $PATH.'{Entity,Resources,Type}',
            $PATH.'**'.DIRECTORY_SEPARATOR.'*Message.php',
            $PATH.'**'.DIRECTORY_SEPARATOR.'*DTO.php',
            $PATH.'**'.DIRECTORY_SEPARATOR.'*Test.php',
        ]);


    /* Типы профилей */
    $services->load(
        $NAMESPACE.'Type\Id\Choice\\',
        $PATH.implode(DIRECTORY_SEPARATOR, ['Type', 'Id', 'Choice'])
    );

    /** @see https://symfony.com/doc/current/service_container/autowiring.html#dealing-with-multiple-implementations-of-the-same-type */

    $services->alias(TypeProfileInterface::class.' $typeProfileUser', TypeProfileUser::class);
    $services->alias(TypeProfileInterface::class.' $typeProfileOrganization', TypeProfileOrganization::class);

};

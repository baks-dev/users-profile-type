<?php
/*
 * This file is part of the FreshCentrifugoBundle.
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace BaksDev\Users\Profile\TypeProfile;

use BaksDev\Users\Profile\TypeProfile\Type\Id\Choice\Collection\TypeProfileInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Id\Choice\TypeProfileOrganization;
use BaksDev\Users\Profile\TypeProfile\Type\Id\Choice\TypeProfileUser;
use DirectoryIterator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class BaksDevUsersProfileTypeProfileBundle extends AbstractBundle
{

    public const NAMESPACE = __NAMESPACE__.'\\';

    public const PATH = __DIR__.DIRECTORY_SEPARATOR;

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $services = $container->services()
            ->defaults()
            ->autowire()
            ->autoconfigure();

        $services->load(self::NAMESPACE, self::PATH)
            ->exclude([
                self::PATH.'{Entity,Resources,Type}',
                self::PATH.'**/*Message.php',
                self::PATH.'**/*DTO.php',
            ]);

        /* Типы профилей */
        $services->load(
            self::NAMESPACE.'Type\Id\Choice\\',
            self::PATH.'Type/Id/Choice'
        );

        /** @see https://symfony.com/doc/current/service_container/autowiring.html#dealing-with-multiple-implementations-of-the-same-type */

        $services->alias(TypeProfileInterface::class.' $typeProfileUser', TypeProfileUser::class);
        $services->alias(TypeProfileInterface::class.' $typeProfileOrganization', TypeProfileOrganization::class);
    }
	
}

<?php
/*
 * Copyright (c) 2022.  Baks.dev <admin@baks.dev>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace BaksDev\Users\Profile\TypeProfile\DataFixtures\TypeProfile\Organization;

use BaksDev\Users\Profile\TypeProfile\Entity as EntityTypeProfile;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\ProfileTypeHandler;
use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Core\Type\Locale\LocaleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

final class TypeProfileOrganizationFixtures extends Fixture
{
	private ProfileTypeHandler $typeHandler;
	
	private SymfonyStyle $io;
	
	
	public function __construct(
		ProfileTypeHandler $typeHandler,
	)
	{
		
		$this->typeHandler = $typeHandler;
		$this->io = new SymfonyStyle(new ArrayInput([]), new ConsoleOutput());
	}
	
	
	public function load(ObjectManager $manager) : void
	{
		# php bin/console doctrine:fixtures:load --append
		
		$isTypeProfile = $manager->getRepository(EntityTypeProfile\Trans\TypeProfileTrans::class)
			->findOneBy(['name' => Trans\TransDTO::NAME[LocaleEnum::DEFAULT_LOCALE]])
		;
		
		if($isTypeProfile === null)
		{
			$TypeProfileDTO = new TypeProfileDTO();
			$TypeProfile = $this->typeHandler->handle($TypeProfileDTO);
			
			if(!$TypeProfile instanceof EntityTypeProfile\TypeProfile)
			{
				$this->io->error(sprintf('Ошика %s при добавлении профиля организации', $TypeProfile));
			}
		}
		
	}
	
}
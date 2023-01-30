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

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit;

use BaksDev\Core\Entity\EntityState;
use BaksDev\Users\Profile\TypeProfile\Entity;
use BaksDev\Core\Type\Modify\ModifyActionEnum;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ProfileTypeHandler
{
	private EntityManagerInterface $entityManager;
	
	private ValidatorInterface $validator;
	
	private LoggerInterface $logger;
	
	
	public function __construct(
		EntityManagerInterface $entityManager,
		ValidatorInterface $validator,
		LoggerInterface $logger,
	)
	{
		$this->entityManager = $entityManager;
		$this->validator = $validator;
		$this->logger = $logger;
	}
	
	
	public function handle(
		Entity\Event\TypeProfileEventInterface $command,
	) : string|Entity\TypeProfile
	{
		
		/* Валидация */
		$errors = $this->validator->validate($command);
		
		if(count($errors) > 0)
		{
			$uniqid = uniqid('', false);
			$errorsString = (string) $errors;
			$this->logger->error($uniqid.': '.$errorsString);
			
			return $uniqid;
		}
		
		if($command->getEvent())
		{
			$Event = $this->entityManager->getRepository(Entity\Event\TypeProfileEvent::class)->find(
				$command->getEvent()
			);
		}
		else
		{
			$Event = new Entity\Event\TypeProfileEvent();
			$this->entityManager->persist($Event);
		}
		
		$Event->setEntity($command);
		
		/** @var Entity\TypeProfile $TypeProfile */
		if($Event->getProfile())
		{
			$TypeProfile = $this->entityManager->getRepository(Entity\TypeProfile::class)->findOneBy(
				['event' => $command->getEvent()]
			);
			
			if(empty($TypeProfile))
			{
				$uniqid = uniqid('', false);
				$errorsString = sprintf(
					'%s: Ошибка при получении TypeProfile с событием id: %s',
					self::class,
					$Event->getEvent()
				);
				$this->logger->error($uniqid.': '.$errorsString);
				
				return $uniqid;
			}
		}
		else
		{
			$TypeProfile = new Entity\TypeProfile();
			$this->entityManager->persist($TypeProfile);
			
			$Event->setProfile($TypeProfile);
			
		}
		
		/** Удаляем отстутсвующие объекты коллекци
		 *
		 * @see EntityState
		 */
		foreach($Event->getRemoveEntity() as $remove)
		{
			$this->entityManager->remove($remove);
		}
		
		$TypeProfile->setEvent($Event);
		$this->entityManager->flush();
		
		return $TypeProfile;
	}
	
}
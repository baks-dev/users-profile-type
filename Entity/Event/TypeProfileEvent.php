<?php
/*
 *  Copyright 2023.  Baks.dev <admin@baks.dev>
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
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 *
 *
 */

namespace BaksDev\Users\Profile\TypeProfile\Entity\Event;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Entity\EntityState;
use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Core\Type\Modify\Modify\ModifyActionNew;
use BaksDev\Core\Type\Modify\Modify\ModifyActionUpdate;
use BaksDev\Users\Profile\TypeProfile\Entity\Info\TypeProfileInfo;
use BaksDev\Users\Profile\TypeProfile\Entity\Modify\TypeProfileModify;
use BaksDev\Users\Profile\TypeProfile\Entity\Section\TypeProfileSection;
use BaksDev\Users\Profile\TypeProfile\Entity\Trans\TypeProfileTrans;
use BaksDev\Users\Profile\TypeProfile\Entity\TypeProfile;
use BaksDev\Users\Profile\TypeProfile\Type\Event\TypeProfileEventUid;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

/* События Profile */


#[ORM\Entity]
#[ORM\Table(name: 'type_users_profile_event')]
#[ORM\Index(columns: ['profile'])]
class TypeProfileEvent extends EntityEvent
{
	const TABLE = 'type_users_profile_event';
	
	/** ID */
    #[Assert\NotBlank]
    #[Assert\Uuid]
	#[ORM\Id]
	#[ORM\Column(type: TypeProfileEventUid::TYPE)]
	private TypeProfileEventUid $id;
	
	/** ID Profile */
    #[Assert\NotBlank]
    #[Assert\Uuid]
	#[ORM\Column(type: TypeProfileUid::TYPE, nullable: false)]
	private ?TypeProfileUid $profile = null;
	
	/** Перевод */
    #[Assert\Valid]
	#[ORM\OneToMany(targetEntity: TypeProfileTrans::class, mappedBy: 'event', cascade: ['all'])]
	private Collection $translate;
	
	/** Секции для профиля */
    #[Assert\Valid]
	#[ORM\OneToMany(targetEntity: TypeProfileSection::class, mappedBy: 'event', cascade: ['all'])]
	#[ORM\OrderBy(['sort' => 'ASC'])]
	private Collection $section;
	
	/** Сортировка */
    #[Assert\NotBlank]
    #[Assert\Length(max: 3)]
    #[Assert\Range(min: 0, max: 999)]
	#[ORM\Column(type: Types::SMALLINT, options: ['default' => 500])]
	private int $sort = 500;
	
	/** Модификатор */
    #[Assert\Valid]
	#[ORM\OneToOne(targetEntity: TypeProfileModify::class, mappedBy: 'event', cascade: ['all'])]
	private TypeProfileModify $modify;

    /** Информация о типе профиля */
    #[Assert\Valid]
    #[ORM\OneToOne(targetEntity: TypeProfileInfo::class, mappedBy: 'event', cascade: ['all'])]
    private ?TypeProfileInfo $info = null;

	public function __construct()
	{
		$this->id = new TypeProfileEventUid();
		$this->modify = new TypeProfileModify($this);
	}


    public function __clone()
    {
        $this->id = clone $this->id;
    }

	public function __toString(): string
	{
		return $this->id;
	}
	
	
	public function getId() : TypeProfileEventUid
	{
		return $this->id;
	}
	
	
	public function getProfile() : ?TypeProfileUid
	{
		return $this->profile;
	}
	
	
	public function setMain(TypeProfileUid|TypeProfile $profile) : void
	{
		$this->profile = $profile instanceof TypeProfile ? $profile->getId() : $profile;
	}
	
	
	public function getDto($dto): mixed
	{
        $dto = is_string($dto) && class_exists($dto) ? new $dto() : $dto;

		if($dto instanceof TypeProfileEventInterface || $dto instanceof self)
		{
			return parent::getDto($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}
	
	
	public function setEntity($dto): mixed
	{
		if($dto instanceof TypeProfileEventInterface || $dto instanceof self)
		{
			return parent::setEntity($dto);
		}
		
		throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
	}

	
	public function getNameByLocale(Locale $locale) : ?string
	{
		$name = null;
		
		/** @var TypeProfileTrans $trans */
		foreach($this->translate as $trans)
		{
			if($name = $trans->name($locale))
				break;
		}
		
		return $name;
	}
	
}
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

namespace BaksDev\Users\Profile\Type\Entity\Event;

use BaksDev\Users\Profile\Type\Entity\Modify\TypeProfileModify;
use BaksDev\Users\Profile\Type\Entity\Event\TypeProfileEventInterface;
use BaksDev\Users\Profile\Type\Entity\Section\TypeProfileSection;
use BaksDev\Users\Profile\Type\Entity\Trans\TypeProfileTrans;
use BaksDev\Users\Profile\Type\Entity\TypeProfile;
use BaksDev\Users\Profile\Type\Type\Event\TypeProfileEventUid;
use BaksDev\Users\Profile\Type\Type\Id\TypeProfileUid;
//use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Core\Type\Modify\ModifyAction;
use BaksDev\Core\Type\Modify\ModifyActionEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/* События Profile */

#[ORM\Entity]
#[ORM\Table(name: 'type_users_profile_event')]
#[ORM\Index(columns: ['profile'])]
class TypeProfileEvent extends EntityEvent
{
    const TABLE = 'type_users_profile_event';
    
    /** ID */
    #[ORM\Id]
    #[ORM\Column(type: TypeProfileEventUid::TYPE)]
    protected TypeProfileEventUid $id;
    
    /** ID Profile */
    #[ORM\Column(type: TypeProfileUid::TYPE, nullable: false)]
    protected ?TypeProfileUid $profile = null;
    
    /** Перевод */
    #[ORM\OneToMany(mappedBy: 'event', targetEntity: TypeProfileTrans::class, cascade: ['all'])]
    protected Collection $translate;
    
    /** Секции для профиля */
    #[ORM\OneToMany(mappedBy: 'event', targetEntity: TypeProfileSection::class, cascade: ['all'])]
    #[ORM\OrderBy(['sort' => 'ASC'])]
    protected Collection $section;
    
    /** Сортировка */
    #[ORM\Column(type: Types::SMALLINT, length: 3, options: ['default' => 500])]
    protected int $sort = 500;
    
    /** Модификатор */
    #[ORM\OneToOne(mappedBy: 'event', targetEntity: TypeProfileModify::class, cascade: ['all'])]
    protected TypeProfileModify $modify;
    
    public function __construct()
    {
       $this->id = new TypeProfileEventUid();

        //$this->translate = new ArrayCollection();
        //$this->section = new ArrayCollection();
    
        $this->modify = new TypeProfileModify($this, new ModifyAction(ModifyActionEnum::NEW));
//
//        $section = new Section();
//        $this->addSection($section);

    }
    
    /**
     * @return TypeProfileEventUid
     */
    public function getId() : TypeProfileEventUid
    {
        return $this->id;
    }
    
    /**
     * @return TypeProfileUid|null
     */
    public function getProfile() : ?TypeProfileUid
    {
        return $this->profile;
    }
    
    /**
     * @param TypeProfileUid|null $profile
     */
    public function setProfile(TypeProfileUid|TypeProfile $profile) : void
    {
        $this->profile = $profile instanceof TypeProfile ? $profile->getId() : $profile;
    }
    
    
    /**
     * @param $dto
     * @return mixed
     * @throws Exception
     */
    public function getDto($dto) : mixed
    {
        if($dto instanceof TypeProfileEventInterface)
        {
            return parent::getDto($dto);
        }
        
        throw new \InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }
    
    /**
     * @param $dto
     * @return mixed
     * @throws Exception
     */
    public function setEntity($dto) : mixed
    {
        if($dto instanceof TypeProfileEventInterface)
        {
            return parent::setEntity($dto);
        }
        
        throw new \InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }
    
    public function isModifyActionEquals(ModifyActionEnum $action) : bool
    {
        return $this->modify->equals($action);
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
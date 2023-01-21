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

namespace BaksDev\Users\Profile\Type\Entity;


use BaksDev\Users\Profile\Type\Type\Settings\TypeProfileSettingsIdentifier;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/* Настройки сущности Profile */

#[ORM\Entity]
#[ORM\Table(name: 'type_users_profile_settings')]
class Settings
{
    const TABLE = 'type_users_profile_settings';

    /** ID */
    #[ORM\Id]
    #[ORM\Column(type: TypeProfileSettingsIdentifier::TYPE)]
    private TypeProfileSettingsIdentifier $id;

    /** Очищать корзину старше n дней */
    #[ORM\Column(name: 'settings_truncate', type: Types::SMALLINT, length: 3, nullable: false)]
    private int $settingsTruncate = 365;
    
    
    /** Очищать события старше n дней */
    #[ORM\Column(name: 'settings_history', type: Types::SMALLINT, length: 3, nullable: false)]
    private int $settingsHistory = 365;

    public function __construct() { $this->id = new TypeProfileSettingsIdentifier();  }

    /**
    * @return TypeProfileSettingsIdentifier
    */
    public function getId() : TypeProfileSettingsIdentifier
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSettingsTruncate() : int
    {
        return $this->settingsTruncate;
    }
    
    /**
     * @param int $settingsTruncate
     */
    public function setSettingsTruncate(int $settingsTruncate) : void
    {
        $this->settingsTruncate = $settingsTruncate;
    }
    
    /**
     * @return int
     */
    public function getSettingsHistory() : int
    {
        return $this->settingsHistory;
    }
    
    /**
     * @param int $settingsHistory
     */
    public function setSettingsHistory(int $settingsHistory) : void
    {
        $this->settingsHistory = $settingsHistory;
    }
}

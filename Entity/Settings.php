<?php
/*
 *  Copyright 2025.  Baks.dev <admin@baks.dev>
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

namespace BaksDev\Users\Profile\TypeProfile\Entity;

use BaksDev\Users\Profile\TypeProfile\Type\Settings\TypeProfileSettingsIdentifier;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/* Настройки сущности Profile */

#[ORM\Entity]
#[ORM\Table(name: 'type_users_profile_settings')]
class Settings
{
    /**
     * Идентификатор
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: TypeProfileSettingsIdentifier::TYPE)]
    private TypeProfileSettingsIdentifier $id;

    /**
     * Очищать корзину старше n дней
     */
    #[Assert\NotBlank]
    #[Assert\Length(max: 3)]
    #[Assert\Range(max: 365)]
    #[ORM\Column(name: 'settings_truncate', type: Types::SMALLINT)]
    private int $settingsTruncate = 30;

    /**
     * Очищать события старше n дней
     */
    #[Assert\NotBlank]
    #[Assert\Length(max: 3)]
    #[Assert\Range(max: 365)]
    #[ORM\Column(name: 'settings_history', type: Types::SMALLINT)]
    private int $settingsHistory = 30;


    public function __construct()
    {
        $this->id = new TypeProfileSettingsIdentifier();
    }


}

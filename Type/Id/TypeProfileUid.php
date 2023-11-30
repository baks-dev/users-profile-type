<?php
/*
*  Copyright Baks.dev <admin@baks.dev>
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

namespace BaksDev\Users\Profile\TypeProfile\Type\Id;

use App\Kernel;
use BaksDev\Core\Type\UidType\Uid;
use Symfony\Component\Uid\AbstractUid;

final class TypeProfileUid extends Uid
{

	public const TYPE = 'profile';

    public const TEST = '018ad881-fd9e-73d2-aea3-75c908fbfbb8';

    /**
     * Идентификаторы профилей пользователя
     */
    private const ORGANIZATION = '0189c5ba-4098-7ea6-a45a-1053b7087e44';

    public const USER = '0189c5ba-6ef8-7a75-94e8-4cec1ddaa3ff';
	
	private mixed $option;

	private mixed $attr;

	public function __construct(
        AbstractUid|string|null $value = null,
        mixed $option = null,
        mixed $attr = null
    )
	{
        parent::__construct($value);

		$this->option = $option;
		$this->attr = $attr;
	}
	
	
	public function getOption(): mixed
	{
		return $this->option;
	}

	public function getAttr(): mixed
	{
		return $this->attr;
	}

    public static function organizationProfileType(): self
    {
        return new self(self::ORGANIZATION);
    }

    public static function userProfileType(): self
    {
        return new self(self::USER);
    }

}
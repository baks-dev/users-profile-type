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

use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use BaksDev\Core\Type\UidType\UidType;
use Doctrine\DBAL\Types\Types;

final class TypeProfileType extends UidType
{
	
	public function getClassType(): string
	{
		return TypeProfileUid::class;
	}
	
	
	public function getName(): string
	{
        return TypeProfileUid::TYPE;
	}
	
}
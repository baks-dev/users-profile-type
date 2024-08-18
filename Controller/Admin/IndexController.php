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

namespace BaksDev\Users\Profile\TypeProfile\Controller\Admin;

use BaksDev\Core\Controller\AbstractController;
use BaksDev\Core\Form\Search;
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use BaksDev\Users\Profile\TypeProfile\Repository\AllProfileType\AllProfileTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[RoleSecurity('ROLE_PROFILE')]
final class IndexController extends AbstractController
{
    #[Route('/admin/profiles/{page<\d+>}', name: 'admin.index', methods: ['GET', 'POST',])]
    public function index(
        Request $request,
        AllProfileTypeInterface $allProfileType,
        int $page = 0,
    ): Response {
        // Поиск
        $search = new Search\SearchDTO();
        $searchForm = $this->createForm(Search\SearchForm::class, $search);
        $searchForm->handleRequest($request);

        // Получаем список
        $query = $allProfileType
            ->search($search)
            ->find();

        return $this->render(
            [
                'query' => $query,
                'search' => $searchForm->createView(),
            ]
        );
    }
}

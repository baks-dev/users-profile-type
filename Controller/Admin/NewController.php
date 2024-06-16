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

namespace BaksDev\Users\Profile\TypeProfile\Controller\Admin;

use BaksDev\Core\Controller\AbstractController;
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use BaksDev\Users\Profile\TypeProfile\Entity\TypeProfile;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\TypeProfileDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\TypeProfileForm;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\TypeProfileHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[RoleSecurity('ROLE_PROFILE_NEW')]
final class NewController extends AbstractController
{
    #[Route('/admin/profile/new', name: 'admin.newedit.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        TypeProfileHandler $handler,
    ): Response {
        $profile = new TypeProfileDTO();

        // Форма добавления
        $form = $this->createForm(TypeProfileForm::class, $profile);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->refreshTokenForm($form);

            $TypeProfile = $handler->handle($profile);

            if($TypeProfile instanceof TypeProfile)
            {
                $this->addFlash('success', 'admin.success.new', 'admin.profile.type');

                return $this->redirectToRoute('users-profile-type:admin.index');
            }

            $this->addFlash('danger', 'admin.danger.new', 'admin.profile.type', $TypeProfile);

            return $this->redirectToRoute('users-profile-type:admin.index');
        }

        return $this->render(['form' => $form->createView()]);
    }
}

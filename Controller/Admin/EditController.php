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

namespace BaksDev\Users\Profile\TypeProfile\Controller\Admin;

use BaksDev\Core\Controller\AbstractController;
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Entity\TypeProfile;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\TypeProfileDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\TypeProfileForm;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\TypeProfileHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[RoleSecurity('ROLE_PROFILE_EDIT')]
final class EditController extends AbstractController
{
    #[Route('/admin/profile/edit/{id}', name: 'admin.newedit.edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        #[MapEntity] TypeProfileEvent $Event,
        TypeProfileHandler $handler,
    ): Response
    {
        $TypeProfileDTO = new TypeProfileDTO();
        $Event->getDto($TypeProfileDTO);

        // Форма добавления
        $form = $this
            ->createForm(
                type: TypeProfileForm::class,
                data: $TypeProfileDTO,
                options: ['action' => $this->generateUrl(
                    'users-profile-type:admin.newedit.edit', ['id' => $Event->getId()],
                )],
            )
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->refreshTokenForm($form);

            $TypeProfile = $handler->handle($TypeProfileDTO);

            if($TypeProfile instanceof TypeProfile)
            {
                $this->addFlash('success', 'admin.success.update', 'admin.profile.type');

                return $this->redirectToRoute('users-profile-type:admin.index');
            }

            $this->addFlash('danger', 'admin.danger.update', 'admin.profile.type', $TypeProfile);

            return $this->redirectToRoute('users-profile-type:admin.index');
        }

        return $this->render(['form' => $form->createView()]);
    }
}

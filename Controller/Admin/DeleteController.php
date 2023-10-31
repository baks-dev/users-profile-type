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
use BaksDev\Users\Profile\TypeProfile\Entity\Event\TypeProfileEvent;
use BaksDev\Users\Profile\TypeProfile\Entity\TypeProfile;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\Delete\DeleteTypeProfileDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\Delete\DeleteTypeProfileForm;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\Delete\DeleteTypeProfileHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[RoleSecurity('ROLE_PROFILE_DELETE')]
final class DeleteController extends AbstractController
{
    #[Route('/admin/profile/delete/{id}', name: 'admin.delete', methods: ['GET', 'POST'])]
    public function delete(
        Request $request,
        TypeProfileEvent $Event,
        DeleteTypeProfileHandler $handler,
    ): Response {
        $TypeProfileDTO = new DeleteTypeProfileDTO();
        $Event->getDto($TypeProfileDTO);

        $form = $this->createForm(DeleteTypeProfileForm::class, $TypeProfileDTO, [
            'action' => $this->generateUrl('users-profile-type:admin.delete', ['id' => $TypeProfileDTO->getEvent()]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $form->has('delete'))
        {
            $GroupEvent = $handler->handle($TypeProfileDTO);

            if ($GroupEvent instanceof TypeProfile) {
                $this->addFlash('admin.form.delete.header', 'admin.success.delete', 'admin.profile.type');

                return $this->redirectToRoute('users-profile-type:admin.index');
            }

            $this->addFlash('admin.form.delete.header', 'admin.danger.delete', 'admin.profile.type', $GroupEvent);

            return $this->redirectToRoute('users-profile-type:admin.index', status: 400);
        }

        return $this->render(
            [
                'form' => $form->createView(),
                'name' => $Event->getNameByLocale($this->getLocale()), // название согласно локали
            ]
        );
    }
}

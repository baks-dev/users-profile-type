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

namespace BaksDev\Users\Profile\TypeProfile\Command\Upgrade;

use BaksDev\Auth\Email\Type\Email\AccountEmail;
use BaksDev\Core\Type\Field\InputField;
use BaksDev\Field\Pack\Contact\Type\ContactField;
use BaksDev\Field\Pack\Inn\Type\InnField;
use BaksDev\Field\Pack\Invoice\Type\InvoiceField;
use BaksDev\Field\Pack\Kpp\Type\KppField;
use BaksDev\Field\Pack\Okpo\Type\OkpoField;
use BaksDev\Field\Pack\Orgn\Type\OrgnField;
use BaksDev\Field\Pack\Phone\Type\PhoneField;
use BaksDev\Users\Address\Type\AddressField\AddressField;
use BaksDev\Users\Profile\TypeProfile\Entity\TypeProfile;
use BaksDev\Users\Profile\TypeProfile\Repository\ExistTypeProfile\ExistTypeProfileInterface;
use BaksDev\Users\Profile\TypeProfile\Type\Id\Choice\TypeProfileIndividual;
use BaksDev\Users\Profile\TypeProfile\Type\Id\TypeProfileUid;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\SectionFieldDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\Trans\SectionFieldTransDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\SectionDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Trans\SectionTransDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Trans\TransDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\TypeProfileDTO;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\TypeProfileHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(
    name: 'baks:users-profile-type:individual',
    description: 'Добавляет тип профилей пользователя «Индивидуальный предприниматель»',
)]
class UpgradeProfileTypeIndividualCommand extends Command
{
    private ExistTypeProfileInterface $existTypeProfile;
    private TranslatorInterface $translator;
    private TypeProfileHandler $profileHandler;

    public function __construct(
        TranslatorInterface $translator,
        ExistTypeProfileInterface $existTypeProfile,
        TypeProfileHandler $profileHandler,
    )
    {
        parent::__construct();

        $this->existTypeProfile = $existTypeProfile;
        $this->translator = $translator;
        $this->profileHandler = $profileHandler;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $TypeProfileUid = new TypeProfileUid(TypeProfileIndividual::class);

        /** Проверяем наличие типа Юр. лицо */
        $exists = $this->existTypeProfile->isExistTypeProfile($TypeProfileUid);

        if(!$exists)
        {
            $io = new SymfonyStyle($input, $output);
            $io->text('Добавляем тип профиля «Индивидуальный предприниматель»');

            $TypeProfileDTO = new TypeProfileDTO();
            $TypeProfileDTO->setSort(TypeProfileIndividual::priority());
            $TypeProfileDTO->setProfile($TypeProfileUid);

            $TypeProfileTranslateDTO = $TypeProfileDTO->getTranslate();


            /**
             * Присваиваем настройки локали типа профиля
             *
             * @var TransDTO $ProfileTrans
             */
            foreach($TypeProfileTranslateDTO as $ProfileTrans)
            {
                $name = $this->translator->trans('name', domain: 'individual.type', locale: $ProfileTrans->getLocal()->getLocalValue());
                $desc = $this->translator->trans('desc', domain: 'individual.type', locale: $ProfileTrans->getLocal()->getLocalValue());

                $ProfileTrans->setName($name);
                $ProfileTrans->setDescription($desc);
            }

            /**
             * Создаем секцию Контактные данные
             */
            $SectionDTO = new SectionDTO();
            $SectionDTO->setSort(100);

            /** @var SectionTransDTO $SectionTrans */
            foreach($SectionDTO->getTranslate() as $SectionTrans)
            {
                $name = $this->translator->trans('section.contact.name', domain: 'individual.type', locale: $SectionTrans->getLocal()->getLocalValue());
                $desc = $this->translator->trans('section.contact.desc', domain: 'individual.type', locale: $SectionTrans->getLocal()->getLocalValue());

                $SectionTrans->setName($name);
                $SectionTrans->setDescription($desc);
            }

            /* Добавляем поля для заполнения */

            $fields = ['address', 'contact', 'phone', 'mail'];

            foreach($fields as $sort => $field)
            {
                $SectionFieldDTO = new SectionFieldDTO();
                $SectionFieldDTO->setSort($sort);
                $SectionFieldDTO->setPublic(true);
                $SectionFieldDTO->setRequired(true);

                /** По умолчанию все поля input */
                $SectionFieldDTO->setType(new InputField('input_field'));

                if($field === 'contact')
                {
                    $SectionFieldDTO->setType(new InputField(ContactField::TYPE));
                }

                if($field === 'phone')
                {
                    $SectionFieldDTO->setType(new InputField(PhoneField::TYPE));
                }

                if($field === 'mail')
                {
                    $SectionFieldDTO->setType(new InputField(AccountEmail::TYPE));
                }

                if($field === 'address')
                {
                    $SectionFieldDTO->setType(new InputField(AddressField::TYPE));
                }

                /** @var SectionFieldTransDTO $SectionFieldTrans */
                foreach($SectionFieldDTO->getTranslate() as $SectionFieldTrans)
                {
                    $name = $this->translator->trans('section.contact.field.'.$field.'.name', domain: 'individual.type', locale: $SectionFieldTrans->getLocal()->getLocalValue());
                    $desc = $this->translator->trans('section.contact.field.'.$field.'.desc', domain: 'individual.type', locale: $SectionFieldTrans->getLocal()->getLocalValue());

                    $SectionFieldTrans->setName($name);
                    $SectionFieldTrans->setDescription($desc);
                }


                $SectionDTO->addField($SectionFieldDTO);
            }

            $TypeProfileDTO->addSection($SectionDTO);


            /**
             * Создаем секцию Официальные реквизиты
             */
            $SectionDTO = new SectionDTO();
            $SectionDTO->setSort(200);

            /** @var SectionTransDTO $SectionTrans */
            foreach($SectionDTO->getTranslate() as $SectionTrans)
            {
                $name = $this->translator->trans('section.requisite.name', domain: 'individual.type', locale: $SectionTrans->getLocal()->getLocalValue());
                $desc = $this->translator->trans('section.requisite.desc', domain: 'individual.type', locale: $SectionTrans->getLocal()->getLocalValue());

                $SectionTrans->setName($name);
                $SectionTrans->setDescription($desc);
            }

            /* Добавляем поля для заполнения */
            $fields = ['inn', 'orgn', 'okpo'];

            foreach($fields as $sort => $field)
            {
                $SectionFieldDTO = new SectionFieldDTO();
                $SectionFieldDTO->setSort($sort);
                $SectionFieldDTO->setPublic(true);
                $SectionFieldDTO->setRequired(true);
                $SectionFieldDTO->setType(new InputField('input_field'));

                if($field === 'inn')
                {
                    $SectionFieldDTO->setType(new InputField(InnField::TYPE));
                }

                if($field === 'orgn')
                {
                    $SectionFieldDTO->setType(new InputField(OrgnField::TYPE));
                }

                if($field === 'okpo')
                {
                    $SectionFieldDTO->setType(new InputField(OkpoField::TYPE));
                }


                /** @var SectionFieldTransDTO $SectionFieldTrans */
                foreach($SectionFieldDTO->getTranslate() as $SectionFieldTrans)
                {
                    $name = $this->translator->trans('section.requisite.field.'.$field.'.name', domain: 'individual.type', locale: $SectionFieldTrans->getLocal()->getLocalValue());
                    $desc = $this->translator->trans('section.requisite.field.'.$field.'.desc', domain: 'individual.type', locale: $SectionFieldTrans->getLocal()->getLocalValue());

                    $SectionFieldTrans->setName($name);
                    $SectionFieldTrans->setDescription($desc);
                }

                $SectionDTO->addField($SectionFieldDTO);
            }

            $TypeProfileDTO->addSection($SectionDTO);


            $handle = $this->profileHandler->handle($TypeProfileDTO);

            if(!$handle instanceof TypeProfile)
            {
                $io->success(
                    sprintf('Ошибка %s при типа профиля', $handle),
                );

                return Command::FAILURE;
            }

        }

        return Command::SUCCESS;
    }

    /** Чам выше число - тем первым в итерации будет значение */
    public static function priority(): int
    {
        return 99;
    }
}

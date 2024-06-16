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

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit;

use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\SectionForm;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Trans\TransForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TypeProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('info', Info\TypeProfileInfoForm::class);

        $builder->add(
            'sort',
            IntegerType::class,
            [
                'label' => false,
                'attr' => ['min' => 0, 'max' => 999],
            ]
        );

        /* CollectionType */
        $builder->add('translate', CollectionType::class, [
            'entry_type' => TransForm::class,
            'entry_options' => ['label' => false],
            'label' => false,
            'by_reference' => false,
            'allow_delete' => true,
            'allow_add' => true,
        ]);

        $builder->add('section', CollectionType::class, [
            'entry_type' => SectionForm::class,
            'entry_options' => ['label' => false],
            'label' => false,
            'by_reference' => false,
            'allow_delete' => true,
            'allow_add' => true,
            'prototype_name' => '__section__',
        ]);

        /* Сохранить ******************************************************/
        $builder->add(
            'Save',
            SubmitType::class,
            [
                'label' => 'Save', 'label_html' => true,
                'attr' => ['class' => 'btn-primary'],
            ]
        );
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => TypeProfileDTO::class,
                'method' => 'POST',
                'attr' => ['class' => 'd-flex flex-column'],
            ]
        );
    }

}

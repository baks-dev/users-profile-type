<?php
/*
 *  Copyright 2022.  Baks.dev <admin@baks.dev>
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

namespace BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields;

use BaksDev\Core\Services\Fields\FieldsChoice;
use BaksDev\Core\Services\Fields\FieldsChoiceInterface;
use BaksDev\Core\Type\Field\InputField;
use BaksDev\Users\Profile\TypeProfile\UseCase\Admin\NewEdit\Section\Fields\Trans\SectionFieldTransForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

final class SectionFieldForm extends AbstractType
{
	//    private $locale;
	//
	//    public function __construct(TranslatorInterface $translator)
	//    {
	//        $this->locale = $translator->getLocale();
	//    }
	
	private TranslatorInterface $translator;
	
	private FieldsChoice $fields;
	
	
	public function __construct(FieldsChoice $fields, TranslatorInterface $translator)
	{
		
		$this->translator = $translator;
		$this->fields = $fields;
	}
	
	
	public function buildForm(FormBuilderInterface $builder, array $options) : void
	{
		
		$builder->add
		(
			'sort',
			IntegerType::class,
			[
				'label' => false,
				'attr' => ['min' => 0, 'max' => 999],
			]
		);
		
		/* CollectionType */
		$builder->add('translate', CollectionType::class, [
			'entry_type' => SectionFieldTransForm::class,
			'entry_options' => ['label' => false],
			'label' => false,
			'by_reference' => false,
			'allow_delete' => true,
			'allow_add' => true,
			'prototype_name' => '__trans__',
		]);
		
		/** Тип поля (input, select, textarea ....) */
		
		$builder->add
		(
			'type',
			ChoiceType::class,
			[
				'required' => false,
				'choices' => $this->fields->getFields(),
				'choice_value' => function($choice) {
					return $choice instanceof FieldsChoiceInterface ? $choice?->type() : $choice;
				},
				'choice_label' => function($choice) {
					return $this->translator->trans('label', domain: $choice->domain());
				},
			]
		);
		
		$builder->get('type')->addModelTransformer(
			new CallbackTransformer(
				function($type) {
					return $type;
				},
				function($type) {
					return $type instanceof FieldsChoiceInterface ? new InputField($type) : null;
				}
			)
		);
		
		/** Обязательное к заполнению */
		
		$builder->add('required', CheckboxType::class, [
			'required' => false,
		]);
		
		/** Публичное свойтсво */
		
		$builder->add('public', CheckboxType::class, [
			'required' => false,
		]);
		
		/** Присутствует в карточке */
		
		$builder->add('card', CheckboxType::class, [
			'required' => false,
		]);
		
		$builder->add
		(
			'DeleteField',
			ButtonType::class,
			[
				'label_html' => true,
			]
		);
		
	}
	
	
	public function configureOptions(OptionsResolver $resolver) : void
	{
		$resolver->setDefaults
		(
			[
				'data_class' => SectionFieldDTO::class,
			]
		);
	}
	
}

<?php

namespace App\Form;

use Craue\FormFlowBundle\Form\FormFlow;

class RecipeFormFlow extends FormFlow
{

    protected $allowDynamicStepNavigation = true;

     protected function loadStepsConfig(): array
     {
         return [
             [
                 'label' => '1. Informations',
                 'form_type' => AddRecipeForm::class,
             ],
             [
                 'label' => "2. Ingredients",
                 'form_type' => AddIngredientForm::class
             ],
             [
                 'label' => "3. Instructions",
                 'form_type' => AddDirectivesForm::class
             ]
         ];
     }
}

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
                 'label' => 'Informations',
                 'form_type' => AddRecipeForm::class,
             ],
             [
                 'label' => "Ingredients",
                 'form_type' => AddIngredientForm::class
             ],
             [
                 'label' => "Instructions",
                 'form_type' => AddDirectivesForm::class
             ]
         ];
     }
}

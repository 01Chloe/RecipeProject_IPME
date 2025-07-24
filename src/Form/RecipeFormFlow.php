<?php

namespace App\Form;

use Craue\FormFlowBundle\Form\FormFlow;

class RecipeFormFlow extends FormFlow
{
 protected function loadStepsConfig(): array
 {
     return [
         [
             'label' => 'Information',
             'form_type' => AddRecipeForm::class
         ]
     ];
 }
}

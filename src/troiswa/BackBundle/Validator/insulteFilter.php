<?php

namespace troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class insulteFilter extends Constraint
{

    public $message = "Pas d'insulte s'il vous plais!";

}
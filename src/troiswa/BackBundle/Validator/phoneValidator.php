<?php

namespace troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class phoneValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {


            if(preg_match('`^0[1-68][0-9]{8}$`',$value) == false )
            {
                $this->context->addViolation($constraint->message);

            }


    }
}
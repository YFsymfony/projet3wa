<?php

namespace troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class passwordCheckerValidator extends ConstraintValidator
{

    // $constraint est un objet définit dans passwordChecker qui joue le
    // role d'un construct en qielque sorte
    // on peu donc récupérer les paramètres que l'on veux qui
    // ont étaient définit dedans ( message / regex / minimum )
    // ces valeur sont écrasées si l'on les redéfinit en annotation dans
    // l'entitée User.
    public function validate($value, Constraint $constraint)
    {


            if(strlen($value) < $constraint->minimum )
            {
                $this->context->addViolation($constraint->message);


            }elseif(preg_match($constraint->regex,$value) == false )
                   {
                       $this->context->addViolation($constraint->message);
                   }


    }
}

/*

    class StrongPasswordValidator extends ConstraintValidator
    {
        public function validate($value, Constraint $constraint)
        {
            if(strlen($value) < $constraint->min)
            {
                $this->context->buildViolation($constraint->messageMin)
                    ->setParameter('{{ limit }}', $constraint->min)
                    ->addViolation();
            }
            elseif($constraint->caractere && !preg_match("#[".$constraint->caractere."]#", $value))
            {
                $this->context->buildViolation($constraint->messageCaractere)
                    ->setParameter('{{ valid }}', $constraint->caractere)
                    ->addViolation();
            }

        }
    }

 */
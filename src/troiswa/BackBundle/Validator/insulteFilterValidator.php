<?php

namespace troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class insulteFilterValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {


// Créer un tableau de gros mots
// Si la valeur tapé par l'utilisateur est dans le tableau
// Création d'une erreur

        $insulte = [
            "connard","pédé","enculé"
        ];


        foreach( $insulte as $val  )
        {

            if(preg_match("#\b(".$val.")\b#ui",$value) )
            {
                $this->context->addViolation($constraint->message);

                break;
            }
        }

    }
}
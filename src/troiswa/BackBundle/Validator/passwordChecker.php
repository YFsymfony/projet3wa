<?php

namespace troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class passwordChecker extends Constraint
{

    public $message = "Mot de passe trop simple ! minimum 6 caractères avec une minuscule , une majuscule et un chiffre";

    public $minimum = 6;

    public $regex = "#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{4,8}$#";

}

/*

    class StrongPassword extends Constraint
    {
        public $min = 8;
        public $messageMin = "Votre mot de passe n'est pas assez fort. Il doit faire {{ limit }} caractères";
        public $caractere = "%-_";
        public $messageCaractere = "Votre mot de passe doit contenir les caractères suivants {{ valid }}";

    }
 */
<?php

namespace troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class phone extends Constraint
{

    public $message = "Entrez un numéro de téléphone à dix chiffres valide !";

}
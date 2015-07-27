<?php
namespace troiswa\FrontBundle\Util;

Class Utility
{
    public  function  bonjour()
    {
        return 'bonjour';
    }

    public function trunkTexte()
    {
        function getLittleDescription($texte, $nbchar = 50)
        {
            return (strlen($texte) > $nbchar ? substr(substr($texte,0,$nbchar),0,strrpos(substr($texte,0,$nbchar)," "))."..." : $texte);
        }
    }
}
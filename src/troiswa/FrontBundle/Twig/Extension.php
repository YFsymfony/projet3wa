<?php

namespace troiswa\FrontBundle\Twig;

Class Extension extends \Twig_Extension
{

    public function getFilters()
    {
        return
            [
                // Nom du filter =>$this ( donnée à filtrer par twig )

                'price'=> new \Twig_Filter_Method($this,'priceFilter'),
                'trunk'=> new \Twig_Filter_Method($this,'trunkFilter'),
            ];
    }

    public function priceFilter($number, $decimal = 2, $separatorDecimal = ',',
                                $separatorMille = " ")
    {
        // {{ product.price|price(4,'.',"-") }}    => 100-000-000.00 €
        $price = number_format($number, $decimal, $separatorDecimal, $separatorMille);
        return $price." €";
    }

    public function trunkFilter($texte,$nbr = 50)
    {

        return (strlen($texte) > $nbr ? substr(substr($texte,0,$nbr),0,strrpos(substr($texte,0,$nbr)," "))." ..." : $texte);

    }

    public function getFunctions()
    {
        return
            [
                'age' => new \Twig_Function_Method($this, 'ageFunction')
            ];
    }

    public function ageFunction($date)
    {
        // fonctione uniquement sur un objet date time
        $now = new \DateTime('now');
        $diff = $date->diff($now);
        return $diff->format('%y').'ans';
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'troiswa_front_twig_extension';
    }
}
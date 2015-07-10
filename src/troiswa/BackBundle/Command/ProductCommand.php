<?php
namespace troiswa\BackBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProductCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('product:quantity')
            ->setDescription("Permet d'envoyer un mail des produits dont la quantité est inférieur à 5")
            ->addArgument('nombre', InputArgument::REQUIRED, 'quantité de produit ?')
        ->addOption('message', 'm', InputOption::VALUE_NONE,'Si définie, un petit message apparaitra');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $templating = $this->getContainer()->get('templating');


        $nb= $input->getArgument("nombre");

        $data = $em->getRepository("troiswaBackBundle:Product")
            ->findProductToOrder($nb);

        //die(var_dump($data));



        $message = \Swift_Message::newInstance()
            ->setSubject(" produits à commander")
            ->setFrom("faucher.yoann@gmail.com")
            ->setTo('faucher.yoann@gmail.com')
            ->setBody($templating->render('troiswaBackBundle:Mails:ProductToOrder.html.twig', ['data' => $data]), 'text/html')
            ->addPart($templating->render('troiswaBackBundle:Mails:contact-email.txt.twig', []), 'text/plain');


        //$this->getContainer()->get('mailer')->send($message);

        // DOC : http://symfony.com/fr/doc/current/components/console/introduction.html
        $output->write(" <info>Votre <comment>mail</comment> a bien été <question>envoyer</question></info>");
        $option = $input->getOption('message');
        if($option)
        {
            $output->writeln("<comment>un autre message</comment>");
        }


    }

}
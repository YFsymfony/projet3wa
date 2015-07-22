<?php
namespace troiswa\BackBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use troiswa\BackBundle\Entity\User;

class UserCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        // php app/console troiswa:user:create --help
        $this
            ->setName('pseudo:password')
            ->setDescription("Permet de créer un dummy user avec un mot de passe encrypté")
            ->addArgument('pseudo', InputArgument::REQUIRED, 'quel pseudo ?')
            ->addArgument('password', InputArgument::REQUIRED, 'quel mot de passe ?')
            ->addOption('exist', 'ex', InputOption::VALUE_NONE,'mise a jour du mot de passe de l\'utilisateur');

    }

    // $output est l'objet qui permet d'afficher un message
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // récupère la partie encoder du fichier sécurity.yml
        $factory = $this->getContainer()->get('security.encoder_factory');

        if($input->getOption('exist'))
        {

            $user = $em->getRepository('troiswaBackBundle:User')
                            ->findOneBy(['pseudo'=>$input->getArgument("pseudo")]);

            /*
             *     version methode magique
             *
            $user = $em->getRepository('TroiswaBackBundle:User')
                ->findOneByPseudo($input->getArgument('pseudo'))
            */

            if ($user)
            {
                $encoder = $factory->getEncoder($user);

                //voir ligne 32
                $newPassword = $encoder->encodePassword($input->getArgument('password'), $user->getSalt());

                $user->setPseudo($input->getArgument("pseudo"));
                $user->setPassword($newPassword);
                $em->flush();

                $message= " le mot de passe de l'utilisateur a bien été modifié";
            }
            else
            {
                $message = "pas d'utilisateur avec se pseudo";
            }

        }else
            {
                $userexist = $em->getRepository('troiswaBackBundle:User')
                                ->findOneByPseudo($input->getArgument("pseudo"));

                if(!$userexist )
                {
                    $user = new User();

                    $encoder = $factory->getEncoder($user);

                    // voir ligne 32
                    $newPassword = $encoder->encodePassword($input->getArgument('password'), $user->getSalt());

                    $user->setPassword($newPassword);

                    $user->setPseudo($input->getArgument("pseudo"));

                    $user->setFirstname("gérard");

                    $user->setLastname("mensoif");

                    $randomemail= uniqid()."geradmensoif@gmail.com";

                    $user->setEmail($randomemail);

                    $user->setbirthday(new \DateTime('now'));

                    $user->setTelephone("0123456789");

                    $user->setAdress(" au bout du monde à gauche , au sud de nul part 00700 Groville , Présipauté de Groland");

                    $em -> persist($user);

                    $em->flush();

                    $message = " l'utilisateur a bien été enregistré";

                }else
                {
                    $message = "Un utilisateur utilise déja ce pseudo , choisissez en un autre.";
                }

            }

            $output->writeln($message);


    }

}
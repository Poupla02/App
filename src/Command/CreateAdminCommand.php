<?php

namespace App\Command;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';
    protected static $defaultDescription = 'Cette commande permet de créer des administrateurs';
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $manager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager)
    {

        $this->passwordHasher = $passwordHasher;
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('email', null, InputOption::VALUE_REQUIRED, "Adresse mail de l'utilisateur")
            ->addOption('password', null, InputOption::VALUE_REQUIRED, "Mot de passe de l'utilisateur")
            ->addOption('role', null, InputOption::VALUE_REQUIRED, "Role de l'utilisateur")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getOption('email');
        $password = $input->getOption('password');
        $role = $input->getOption('role');

        if (in_array(null, [$email,$password,$role])) {
            $io->error(sprintf('Ces paramètres suivants sont obligatoires: %s, %s, %s', "--email","--password","--role"));
            return Command::FAILURE;
        }

        //creer un nouveau utilisateur
        $user = (new Admin())
            ->setEmail($email)
            ->setRole($role);
        //hasher son mot de passe
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        //persister en base de donnée
        $this->manager->persist($user);
        $this->manager->flush();

        $io->success(sprintf("Utilisateur crée avec les identifiants suivant :\n Adresse mail: %s\n Mot de passe: %s\n Role: %s", $email, $password, $role));

        return Command::SUCCESS;
    }
}

<?php

namespace App\Command;

use App\Manager\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeAdminCommand extends Command
{
    protected static $defaultName = 'app:make:admin';
    protected static $defaultDescription = 'make admin application';
    /**
     * @var UserManager $userManager
     */
    private $userManager;

    /**
     * MakeAdminCommand constructor.
     * @param UserManager $userManager
     * @param null $name
     */

    public function __construct(UserManager $userManager, $name = null)
    {
        parent::__construct($name);
        $this->userManager = $userManager;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, 'Admin email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Admin password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $emailQuestion = new Question('Provide a email: ', 'admin@admin.com');
        $passwordQuestion = new Question('Provide a password: ');

        $email = $helper->ask($input, $output, $emailQuestion);
        $password = $helper->ask($input, $output, $passwordQuestion);

        $currentUser = $this->userManager->processFully($email, $password)->addRole('ROLE_ADMIN');
        try{
            $this->userManager->seedUser($currentUser);
            $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
            return Command::SUCCESS;
        }catch (\Exception $e){
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}

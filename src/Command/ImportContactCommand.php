<?php

namespace App\Command;

use App\Component\Provider\Google\Runner\GoogleContactRunner;
use App\Repository\QaAccessTokenRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportContactCommand extends Command
{
    protected static $defaultName = 'app:import:contact';
    protected static $defaultDescription = 'Add a short description for your command';
    /**
     * @var \Google_Client
     */
    private $googleClient;
    /**
     * @var GoogleContactRunner $googleContactRunner
     */
    private $googleContactRunner;
    /**
     * @var QaAccessTokenRepository
     */
    private $accessTokenRepository;

    public function __construct(
        \Google_Client $googleClient,
        GoogleContactRunner $contactRunner,
        QaAccessTokenRepository $accessTokenRepository,
        $name = null)
    {
        parent::__construct($name);
        $this->googleClient = $googleClient;
        $this->googleContactRunner = $contactRunner;
        $this->accessTokenRepository = $accessTokenRepository;

    }


    protected function configure(): void
    {
        $this
            ->addArgument('uuid', InputArgument::REQUIRED, 'user identifier')
            ->addArgument('provider', InputArgument::REQUIRED, 'provider')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $uuid = $input->getArgument('uuid');
        $provider = $input->getArgument('provider');
        $refreshToken = $this->accessTokenRepository->getTokenByProviderAndUserIdentifier($uuid, $provider);
        $this->googleContactRunner->setPageSize(10);
        $this->googleContactRunner->iterateOverOtherContact($refreshToken, function($person)use($io){
            if(count($person['names'])){
                $io->writeln('--------------');
                $io->writeln(sprintf(
                    'importing %s %s %s',
                    $person['names'][0]['displayName'],
                    $person['emailAddresses'][0]['value'],
                    $person['photos'][0]['url']
                ));

            }
        });


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}

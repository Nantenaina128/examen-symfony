<?php

namespace App\Command;

use App\Repository\ContractRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:check-contracts')]
class CheckContractsCommand extends Command
{
    private ContractRepository $repository;

    public function __construct(ContractRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contracts = $this->repository->findExpiringSoon();

        if (empty($contracts)) {
            $output->writeln('Aucun contrat à renouveler bientôt.');
        } else {
            $output->writeln("Contrats expirant bientôt :");
            foreach ($contracts as $contract) {
                $output->writeln(" - {$contract->getName()} (fin le " . $contract->getEndDate()->format('Y-m-d') . ")");
            }
        }

        return Command::SUCCESS;
    }
}
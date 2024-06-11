<?php

namespace App\Command;

use App\DataMapper\TransactionMapper;
use App\Service\CommissionCalculator\CommissionCalculator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:commission:calculate')]
class CommissionCalculateCommand extends Command
{
    public function __construct(
        protected TransactionMapper $transactionMapper,
        protected CommissionCalculator $commissionCalculator,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Transactions list file absolute path?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = file($input->getArgument('file'));
        if (!$file) {
            $output->writeln('Please make sure you specified correct absolute path to the file');

            return Command::FAILURE;
        }

        foreach ($file as $json) {
            $data = json_decode($json, true);
            $transactionDTO = $this->transactionMapper->mapToTransactionDTO($data);
            $commissionAmount = $this->commissionCalculator->calculateForTransaction($transactionDTO);
            $output->writeln($commissionAmount);
        }

        return Command::SUCCESS;
    }
}

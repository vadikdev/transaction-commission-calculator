<?php

namespace App\DataMapper;

use App\DTO\TransactionDTO;

class TransactionMapper
{
    public function mapToTransactionDTO(array $data): TransactionDTO
    {
        $transactionDTO = new TransactionDTO();
        $transactionDTO->bin = $data['bin'];
        $transactionDTO->amount = $data['amount'];
        $transactionDTO->currency = $data['currency'];

        return $transactionDTO;
    }
}

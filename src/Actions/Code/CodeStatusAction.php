<?php

declare(strict_types=1);

namespace App\Actions\Code;

use App\Data\Repository\CodeRepositoryInterface;

class CodeStatusAction
{
    /** @var CodeRepositoryInterface */
    private $codeRepository;


    public function __construct(CodeRepositoryInterface $codeRepository)
    {
        $this->codeRepository = $codeRepository;
    }

    public function __invoke(int $userId): array
    {
        $totalCodesSent = $this->codeRepository->getTotalCodesSent();
        $totalCodesSentByUser = $this->codeRepository->getTotalCodesSentByUser(
            $userId
        );

        return [
            'total_codes_sent' => $totalCodesSent,
            'total_codes_sent_by_user' => $totalCodesSentByUser
        ];
    }
}

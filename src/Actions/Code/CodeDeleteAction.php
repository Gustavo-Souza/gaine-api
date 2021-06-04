<?php

declare(strict_types=1);

namespace App\Actions\Code;

use App\Actions\Code\Validation\CodeDeleteValidation;
use App\Data\Exception\ModelNotFoundException;
use App\Data\Repository\CodeRepositoryInterface;
use App\Exception\ValidationException;

class CodeDeleteAction
{
    /** @var CodeRepositoryInterface */
    private $codeRepository;


    public function __construct(CodeRepositoryInterface $codeRepository)
    {
        $this->codeRepository = $codeRepository;
    }

    /**
     * @throws ValidationException
     * @throws ModelNotFoundException
     */
    public function __invoke($requestParams): void
    {
        $params = CodeDeleteValidation::validate($requestParams);

        $this->codeRepository->delete(
            $params->streamer_code,
            $params->code
        );
    }
}

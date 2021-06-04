<?php

declare(strict_types=1);

namespace Test\Unit\Actions\Code;

use App\Actions\Code\CodeStatusAction;
use App\Data\Repository\CodeRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\once;

class CodeStatusActionTest extends TestCase
{
    /** @var CodeRepositoryInterface|MockObject */
    private $codeRepository;


    protected function setUp(): void
    {
        $this->codeRepository = $this->createMock(
            CodeRepositoryInterface::class
        );
    }


    public function testReturnsTotalCodesSentInGeneralAndByTheUser(): void
    {
        // Arrange
        $this->codeRepository
            ->expects(once())
            ->method('getTotalCodesSent')
            ->willReturn(3);
        $this->codeRepository
            ->expects(once())
            ->method('getTotalCodesSentByUser')
            ->willReturn(1);

        $userId = 0;
        $action = new CodeStatusAction($this->codeRepository);
        
        // Act
        $jsonArray = $action->__invoke($userId);

        // Assert
        assertNotEmpty($jsonArray);
        assertArrayHasKey('total_codes_sent', $jsonArray);
        assertArrayHasKey('total_codes_sent_by_user', $jsonArray);
    }
}

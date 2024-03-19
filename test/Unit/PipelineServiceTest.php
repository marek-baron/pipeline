<?php

declare(strict_types=1);

namespace Baron\Pipeline\test\Unit;

use Baron\Pipeline\Pipeline;
use Baron\Pipeline\PipelineService;
use Baron\Pipeline\TaskInterface;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class PipelineServiceTest
 * @package Baron\Pipeline\test\Unit
 * @author Marek Baron<baron.marek@googlemail.com>
 */
class PipelineServiceTest extends TestCase
{

    private PipelineService $pipelineService;
    private MockObject|TaskInterface $task;
    private MockObject|ContainerInterface $container;

    public function setUp(): void
    {
        $this->pipelineService = new PipelineService();
        $this->task = $this->createMock(TaskInterface::class);
        $this->container = $this->createMock(ContainerInterface::class);
    }

    /**
     * @test
     * @return void
     */
    public function checkIfOptionsArePassedIntoPipeline(): void
    {
        $options = ['any' => 'options'];
        $pipeline = $this->pipelineService->create([], Pipeline::class, $options);

        self::assertSame($options, $pipeline->getOptions());
    }

    /**
     * @test
     * @return void
     */
    public function checkIfPsr11ContainerIsUsedCorrectly(): void
    {
        $serviceHash = 'any';
        $this->container
            ->expects(self::once())
            ->method('get')
            ->with($serviceHash)
            ->willReturn($this->task);

        $pipeline = $this->pipelineService->createPsr11($this->container, [$serviceHash]);

        self::assertInstanceOf(
            Pipeline::class,
            $pipeline
        );
    }

    /**
     * @test
     * @return void
     */
    public function checkIfExceptionIsThrownIfInvalidTask(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->pipelineService->create(['WrongType']);
    }
}

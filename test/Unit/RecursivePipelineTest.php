<?php

declare(strict_types=1);

namespace Baron\Pipeline\test\Unit;

use PHPUnit\Framework\TestCase;
use Baron\Pipeline\PayloadInterface;
use Baron\Pipeline\Pipeline;
use Baron\Pipeline\RecursivePipeline;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Class RecursivePipelineTest
 * @package Baron\Pipeline\test\Unit
 * @author Marek Baron<baron.marek@googlemail.com>
 */
class RecursivePipelineTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     * @return void
     */
    public function checkIfItReturnsThePayloadOnEmpty(): void
    {
        $payload = $this->prophesize(PayloadInterface::class)->reveal();

        $pipeline = $this->prophesize(Pipeline::class);
        $pipeline->handle($payload)->will(
            function ($args) {
                return $args[0];
            }
        );
        $obj = ['any' => 'object'];
        $pipeline->getOptions()->willReturn($obj);

        $recursivePipe = new RecursivePipeline();
        $return = $recursivePipe($payload, $pipeline->reveal());

        self::assertSame($payload, $return);
        self::assertSame($obj, $recursivePipe->getOptions());
    }
}

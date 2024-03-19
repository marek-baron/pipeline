<?php
declare(strict_types=1);

namespace Baron\Pipeline\test\Unit;

use PHPUnit\Framework\TestCase;
use Baron\Pipeline\GenericPayload;

/**
 * Class GenericPayloadTest
 * @package Baron\Pipeline\test\Unit
 * @author Marek Baron<baron.marek@googlemail.com>
 */
class GenericPayloadTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function checkIfUnsetValueReturnsNull(): void
    {
        $payload = new GenericPayload();
        $someFunction = 'getFunction';
        self::assertNull($payload->$someFunction());
    }

    /**
     * @test
     * @return void
     */
    public function checkIfSetWorksTogetherWithGet(): void
    {
        $payload = new GenericPayload();
        $value = 'test';
        $setFunction = 'setFunction';
        $payload->$setFunction($value);
        $getFunction = 'getFunction';
        self::assertSame($value, $payload->$getFunction());
    }

    /**
     * @test
     * @dataProvider invalidFunctionsDataProvider
     * @param string $invalidFunction
     * @return void
     */
    public function checkIfExceptionIsThrownOnInvalidFunction($invalidFunction): void
    {
        $payload = new GenericPayload();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Function ' . $invalidFunction . ' does not exists!');

        $payload->$invalidFunction();
    }

    /**
     * @return array
     */
    public function invalidFunctionsDataProvider(): array
    {
        return [
            [''],
            ['set'],
            ['get'],
            ['anyOther']
        ];
    }
}

<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Csv\Exceptions\InvalidColumnException;
use App\Services\Csv\Exceptions\InvalidRowException;
use App\Services\Csv\InputDto;
use App\Services\csv\InputValidator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class InputValidatorTest extends TestCase
{
    private const COLUMN_MAX_LENGTH = 10;

    public function testExceptionWhenRowColumnsCountMoreThanHeader(): void
    {
        $input = InputDto::create(
            ['first', 'second'],
            [
                ['first', 'second', 'third'],
            ]
        );

        $this->expectException(InvalidRowException::class);
        $this->expectExceptionCode(1000);

        $this->validator()->validate($input);
    }

    public function testNoExceptionWhenRowColumnsCountEqualToHeader(): void
    {
        $input = InputDto::create(
            ['first', 'second'],
            [
                ['first', 'second'],
            ]
        );

        $this->validator()->validate($input);

        $this->expectNotToPerformAssertions();
    }

    public function testNoExceptionWhenRowColumnsCountLessThanHeader(): void
    {
        $input = InputDto::create(
            ['first', 'second'],
            [
                ['first', 'second'],
            ]
        );

        $this->validator()->validate($input);

        $this->expectNotToPerformAssertions();
    }

    public function testExceptionWhenHeaderRowLengthMoreThenAcceptable(): void
    {
        $input = InputDto::create(
            [str_repeat('*', self::COLUMN_MAX_LENGTH + 1), 'second'],
            [
                ['first', 'second'],
            ]
        );

        $this->expectException(InvalidColumnException::class);
        $this->expectExceptionCode(2000);

        $this->validator()->validate($input);
    }

    public function testExceptionWhenRowLengthMoreThenAcceptable(): void
    {
        $input = InputDto::create(
            ['first', 'second'],
            [
                ['first', str_repeat('*', self::COLUMN_MAX_LENGTH + 1)],
            ]
        );

        $this->expectException(InvalidColumnException::class);
        $this->expectExceptionCode(2000);

        $this->validator()->validate($input);
    }

    public function testCorrectHeaderRowIndexWhenLengthError(): void
    {
        $input = InputDto::create(
            [str_repeat('*', self::COLUMN_MAX_LENGTH + 1), 'second'],
            [
                ['first', 'second'],
            ]
        );

        try {
            $this->validator()->validate($input);
        } catch (InvalidColumnException $e) {
            self::assertEquals(0, $e->getRowIndex());
        }
    }

    public function testCorrectRowIndexWhenLengthError(): void
    {
        $input = InputDto::create(
            ['first', 'second'],
            [
                ['first', str_repeat('*', self::COLUMN_MAX_LENGTH + 1)],
            ]
        );

        try {
            $this->validator()->validate($input);
        } catch (InvalidColumnException $e) {
            self::assertEquals(1, $e->getRowIndex());
        }
    }

    public function testCorrectHeaderColumnIndexWhenLengthError(): void
    {
        $input = InputDto::create(
            ['second', str_repeat('*', self::COLUMN_MAX_LENGTH + 1)],
            [
                ['first', 'second'],
            ]
        );

        try {
            $this->validator()->validate($input);
        } catch (InvalidColumnException $e) {
            self::assertEquals(1, $e->getColumnIndex());
        }
    }

    public function testCorrectColumnIndexWhenLengthError(): void
    {
        $input = InputDto::create(
            ['first', 'second'],
            [
                [str_repeat('*', self::COLUMN_MAX_LENGTH + 1), 'first'],
            ]
        );

        try {
            $this->validator()->validate($input);
        } catch (InvalidColumnException $e) {
            self::assertEquals(0, $e->getColumnIndex());
        }
    }

    private function validator(): InputValidator
    {
        return new InputValidator(self::COLUMN_MAX_LENGTH);
    }
}

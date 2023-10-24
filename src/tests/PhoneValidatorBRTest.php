<?php

namespace Glima\PhoneValidateBr\tests;

use Glima\PhoneValidateBr\PhoneValidatorBR;
use PHPUnit\Framework\TestCase;

class PhoneValidatorBRTest extends TestCase
{

    /**
     * @dataProvider phoneValidationProvider
     */
    public function testValidatePhone(string $phone, bool $result)
    {
        $this->assertEquals($result, PhoneValidatorBR::validate($phone)->isValid);
    }

    public function phoneValidationProvider(): array
    {
        return [
            ["55 055 9 88888888", true],
            ["55 55 9 88888888", true],
            ["+55(011) 91234-5678", true],
            [" +55(11) 91234-5678 ", true],
            ["+55(11) 1234-5678", true],
            ["(21) 97777-1234", true],
            ["(21) 7777-1234", true],
            ["+55(031) 98652-7890", true],
            ["055(031) 94652-7890", true],
            ["055(031) 4652-7890", true],
            ["+55(31) 94652-7890", true],
            ["+55(31) 4652-7890", true],
            ["055(31) 94652-7890", true],
            ["(041) 8765-4321", true],
            ["+55(051) 7654-3210", true],
            ["(61) 6543-2109", true],
            ["(061) 6543-2109", true],
            ["(055)9 6543-8888", true],
            ["(55) 6543-2109", true],
            ["(061) 9 6543-2109", true],
            ["(55) 66543-2109", false],
            [" +55(11) 81234-5678 ", false],
            ["+43(011) 91234-5678", false],
            ["+55(11) 91234-56455622", false],
            ["(055)7 6543-8888", false],
            ["(010) 9 6543-2109", false],
            ["(009) 9 6543-2109", false],
            ["(01) 9 6543-2109", false],
            ["(010) 8 6543-2109", false],
            ["(009) 5 6543-2109", false],
            ["(01) 2 6543-2109", false],
            ["(01) 9 6543-2109", false],
            ["(009)6543-2109", false],
            ["(01) 6543-2109", false],
            ["55 (010) 9 6543-2109", false],
            ["055 (010) 9 6543-2109", false],
            ["55 (009) 9 6543-2109", false],
            ["055 (009) 9 6543-2109", false],
            ["55 (01) 9 6543-2109", false],
            ["055 (01) 9 6543-2109", false],
            ["55 (010) 8 6543-2109", false],
            ["055 (010) 8 6543-2109", false],
            ["55 (009) 5 6543-2109", false],
            ["055 (009) 5 6543-2109", false],
            ["55 (01) 2 6543-2109", false],
            ["055 (01) 2 6543-2109", false],
            ["55 (01) 9 6543-2109", false],
            ["055 (01) 9 6543-2109", false],
            ["55 (009)6543-2109", false],
            ["055 (009)6543-2109", false],
            ["55 (01) 6543-2109", false],
            ["055 (01) 6543-2109", false],
            ["9876-5432", false],
            ["8765-4321", false],
            ["7654-3210", false]
        ];
    }

}

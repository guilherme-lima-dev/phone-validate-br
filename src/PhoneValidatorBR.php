<?php

namespace Glima\PhoneValidateBr;

use Glima\PhoneValidateBr\Responses\PhoneValidatorResponse;

class PhoneValidatorBR
{

    const DDDS_BR = [
        "11", "12", "13", "14", "15", "16", "17",
        "18", "19", "21", "22", "24", "27", "28",
        "31", "32", "33", "34", "35", "37", "38",
        "41", "42", "43", "44", "45", "46", "47",
        "48", "49", "51", "53", "54", "55", "61",
        "62", "63", "64", "65", "66", "67", "68",
        "69", "71", "73", "74", "75", "77", "79",
        "81", "82", "83", "84", "85", "86", "87",
        "88", "89", "91", "92", "93", "94", "95",
        "96", "97", "98", "99"
    ];

    const DDDS_BR_WITH_ZERO = [
        "011", "012", "013", "014", "015", "016", "017",
        "018", "019", "021", "022", "024", "027", "028",
        "031", "032", "033", "034", "035", "037", "038",
        "041", "042", "043", "044", "045", "046", "047",
        "048", "049", "051", "053", "054", "055", "061",
        "062", "063", "064", "065", "066", "067", "068",
        "069", "071", "073", "074", "075", "077", "079",
        "081", "082", "083", "084", "085", "086", "087",
        "088", "089", "091", "092", "093", "094", "095",
        "096", "097", "098", "099"
    ];

    public static function validate(string $phone): PhoneValidatorResponse
    {

        $message = "O telefone é válido!";
        $phone = preg_replace("/\D/", "", $phone);

        if (self::validationInitialDigits($phone)) {
            $phone = null;
            $message = "Os digitos iniciais não são válidos!";
            return self::handleReturn($phone, false, $message);
        }

        if (self::lengthGreaterThan($phone, 11)) {
            $phone = preg_replace('/^0/', '', $phone, 1);
        }

        if (self::lengthGreaterThanFourteenOrLessThanTen($phone)) {
            $phone = null;
            $message = "A quantidade de digitos desse telefone é inválida!";
            return self::handleReturn($phone, false, $message);
        }

        if (self::lengthGreaterThan($phone, 11)) {
            $phone = preg_replace('/^55/', '', $phone, 1);
        }

        if (
            self::lengthGreaterThan($phone, 10) &&
            substr($phone, 2, 1) != "9" &&
            in_array(substr($phone, 0, 3), self::DDDS_BR_WITH_ZERO)
        ) {
            $phone = preg_replace('/^0/', '', $phone, 1);
        }

        if (self::lengthGreaterThan($phone, 11)) {
            $phone = null;
            $message = "O código de área é invalido, no Brasil o código é 55!";
            return self::handleReturn($phone, false, $message);
        }

        $ddd = substr($phone, 0, 2);

        if (!self::hasValidDDD($ddd)) {
            $phone = null;
            $message = "O DDD é invalido!";
            return self::handleReturn($phone, false, $message);
        }

        if (self::cleanPhoneSizeIsNotValid($phone)) {
            $phone = null;
            $message = "O telefone possui um formato desconhecido!";
            return self::handleReturn($phone, false, $message);
        }

        if (self::hasValidNinthDigit($phone)) {
            $phone = null;
            $message = "O nono digito está incorreto, deve ter o valor 9!";
            return self::handleReturn($phone, false, $message);
        }

        if (self::hasRepeatedNumbers($phone)) {
            $phone = null;
            $message = "O numero possui muitos digitos repetidos!";
            return self::handleReturn($phone, false, $message);
        }

        return self::handleReturn($phone, true, $message);
    }

    private static function hasRepeatedNumbers($phone): bool
    {
        return preg_match('/(\d)\1{6,}/', $phone) == 1;
    }

    public static function hasValidNinthDigit(array|string|null $phone): bool
    {
        return strlen($phone) == 11 && substr($phone, 2, 1) != "9";
    }

    public static function cleanPhoneSizeIsNotValid(array|string|null $phone): bool
    {
        return strlen($phone) < 10;
    }

    public static function hasValidDDD(string $ddd): bool
    {
        return in_array($ddd, self::DDDS_BR);
    }

    public static function lengthGreaterThan(string $phone, int $number): bool
    {
        return strlen($phone) > $number;
    }

    public static function lengthGreaterThanFourteenOrLessThanTen(array|string|null $phone): bool
    {
        return strlen($phone) < 10 || strlen($phone) > 14;
    }

    public static function validationInitialDigits(array|string|null $phone): bool
    {
        return !self::hasValidDDD(substr($phone, 0, 2)) &&
            !in_array(substr($phone, 0, 3), self::DDDS_BR_WITH_ZERO) &&
            !str_starts_with($phone, "055") &&
            !str_starts_with($phone, "55");
    }

    private static function handleReturn($phone, bool $isValid, string $message): PhoneValidatorResponse
    {

        return new PhoneValidatorResponse(
            phone: $phone,
            isValid: $isValid,
            message: $message
        );
    }

}

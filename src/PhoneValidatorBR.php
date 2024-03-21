<?php

namespace Glima\PhoneValidateBr;

use Glima\PhoneValidateBr\Responses\PhoneValidatorResponse;

class PhoneValidatorBR
{
    public const DDD_BR_LIST = [
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

    public static function validate(string $phone): PhoneValidatorResponse
    {

        $message = "O telefone é válido!";
        $phone = preg_replace("/\D/", "", $phone);

        if (!self::haveValidInitialDigits($phone)) {
            $message = "Os digitos iniciais não são válidos!";
            return self::handleReturn(null, false, $message);
        }

        if (self::lengthGreaterThan($phone, 11)) {
            $phone = preg_replace('/^0/', '', $phone, 1);
        }

        if (self::lengthGreaterThanFourteenOrLessThanTen($phone)) {
            $message = "A quantidade de digitos desse telefone é inválida!";
            return self::handleReturn(null, false, $message);
        }

        if (self::lengthGreaterThan($phone, 11)) {
            $phone = preg_replace('/^55/', '', $phone, 1);
        }

        if (
            self::lengthGreaterThan($phone, 10) &&
            $phone[2] !== "9" &&
            in_array(substr($phone, 0, 3), self::getDDDWithZeroList(), true)
        ) {
            $phone = preg_replace('/^0/', '', $phone, 1);
        }

        if (self::lengthGreaterThan($phone, 11)) {
            $message = "O código de área é invalido, no Brasil o código é 55!";
            return self::handleReturn(null, false, $message);
        }

        $ddd = substr($phone, 0, 2);

        if (!self::hasValidDDD($ddd)) {
            $message = "O DDD é invalido!";
            return self::handleReturn(null, false, $message);
        }

        if (self::cleanPhoneSizeIsNotValid($phone)) {
            $message = "O telefone possui um formato desconhecido!";
            return self::handleReturn(null, false, $message);
        }

        if (self::hasValidNinthDigit($phone)) {
            $message = "O nono digito está incorreto, deve ter o valor 9!";
            return self::handleReturn(null, false, $message);
        }

        if (self::isHomeNumber($phone)) {
            $message = "Só é aceito numeros de celular!";
            return self::handleReturn(null, false, $message);
        }

        if (self::hasRepeatedNumbers($phone)) {
            $message = "O numero possui muitos digitos repetidos!";
            return self::handleReturn(null, false, $message);
        }

        return self::handleReturn($phone, true, $message);
    }

    private static function hasRepeatedNumbers($phone): bool
    {
        return preg_match('/(\d)\1{6,}/', $phone) == 1;
    }

    private static function isHomeNumber(string $phone): bool
    {
        return strlen($phone) === 10 && ($phone[2] === "3" || $phone[2] === "2");
    }

    private static function hasValidNinthDigit(array|string|null $phone): bool
    {
        return strlen($phone) === 11 && $phone[2] !== "9";
    }

    private static function cleanPhoneSizeIsNotValid(array|string|null $phone): bool
    {
        return strlen($phone) < 10;
    }

    private static function hasValidDDD(string $ddd): bool
    {
        return in_array($ddd, self::DDD_BR_LIST);
    }

    private static function lengthGreaterThan(string $phone, int $number): bool
    {
        return strlen($phone) > $number;
    }

    private static function lengthGreaterThanFourteenOrLessThanTen(array|string|null $phone): bool
    {
        return strlen($phone) < 10 || strlen($phone) > 14;
    }

    private static function haveValidInitialDigits(array|string|null $phone): bool
    {
        return !self::hasValidDDD(substr($phone, 0, 2)) ||
            !in_array(substr($phone, 0, 3), self::getDDDWithZeroList(), true) ||
            !str_starts_with($phone, "055") ||
            !str_starts_with($phone, "55");
    }

    private static function getDDDWithZeroList(): array
    {
        $DDDWithZeroList = [];
        foreach (self::DDD_BR_LIST as $value) {
            $DDDWithZeroList[] = '0'.$value;
        }

        return $DDDWithZeroList;
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

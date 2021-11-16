<?php


namespace App\Constants;


class ContractStatus
{
    const ARE_CONFIRMING = 0 ;
    const SINGED = 1;
    const HAS_MADE_A_DEPOSIT = 2;
    const FINISH = 3;
    const ALMOST_EXPIRED = 4;
    const EXPIRED = 5 ;
    const TRIAL = 6;
    const CREATING = 7;
    const UNFINISHED_PAYMENT = 8;
    const EXPIRED_TRIAL = 9;
    const TIME_BEFORE_END_CONTRACT = 60;
    public static function get():array {
        return [
            self::ARE_CONFIRMING,
            self::SINGED,
            self::HAS_MADE_A_DEPOSIT,
            self::FINISH,
            self::ALMOST_EXPIRED,
            self::TRIAL,
            self::CREATING
        ];
    }
    public static function signed():array {
        return [
            self::SINGED,
            self::HAS_MADE_A_DEPOSIT,
            self::FINISH,
            self::ALMOST_EXPIRED,
            self::EXPIRED
        ];
    }
    public static function active():array {
        return [
            self::SINGED,
            self::HAS_MADE_A_DEPOSIT,
            self::FINISH,
            self::ALMOST_EXPIRED,
            self::TRIAL
        ];
    }
}

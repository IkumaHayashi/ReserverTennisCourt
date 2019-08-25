<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__).'/../model/ReservationInformation.php');
require_once(dirname(__FILE__).'/../model/Account.php');

class ReservationInformationTest extends TestCase
{
    /**
     * @test
     */
    public function コンストラクタのテスト(): void
    {
        $account = new Account('10008771','1125k');
        $reservation_information = new ReservationInformation($account
                                        , '木ノ下テニスコート'
                                        , array('第１コート（クレー）', '第２コート（クレー）', '第３コート（クレー）')
                                        , new DateTime('2019-09-28 12:30:00'), 4
                                        , 'ハヤシ イクマ'
                                        , '林 郁真'
                                        , '080-5158-7732');
        
        $reservation_units = $reservation_information->get_reservation_units();
        
    /**
     * 予約対象範囲を1回の予約可能単位である1コートあたり2コマ、最大4コマで設定する。
     * ex. 8:30から4時間、第1コート、第2コート、第3コートを指定された場合、下記のように設定する。
     * [0] => ('第1コート', 08:30), ('第1コート', 09:30), ('第2コート', 08:30), ('第2コート', 09:30)
     * [1] => ('第1コート', 10:30), ('第1コート', 11:30), ('第2コート', 10:30), ('第2コート', 11:30)
     * [2] => ('第3コート', 08:30), ('第3コート', 09:30)
     * [3] => ('第3コート', 100:30), ('第3コート', 11:30)
     */
        $actual_reservation_unit = $reservation_units[0][0];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第１コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 12:30:00'), $actual_reservation_unit->datetime);
                                     
    }
}
<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__).'/../model/ReservationInformation.php');
require_once(dirname(__FILE__).'/../model/Account.php');

class ReservationInformationTest extends TestCase
{
    /**
     * @test
     */
    public function コンストラクタのテスト0(): void //TODO: テスト名の見直し
    {
        $account = new Account('10008771','1125k');
        $reservation_information = new ReservationInformation($account
                                        , '木ノ下テニスコート'
                                        , array('第１コート（クレー）')
                                        , new DateTime('2019-09-28 12:30:00'), 1
                                        , 'ハヤシ イクマ'
                                        , '林 郁真'
                                        , '080-5158-7732');
        
        $reservation_units = $reservation_information->get_reservation_units();
        

        $actual_reservation_unit = $reservation_units[0][0];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第１コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 12:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 13:29:59'), $actual_reservation_unit->end_datetime);
    }
    /**
     * @test
     */
    public function コンストラクタのテスト1_1(): void //TODO: テスト名の見直し
    {
        $account = new Account('10008771','1125k');
        $reservation_information = new ReservationInformation($account
                                        , '木ノ下テニスコート'
                                        , array('第１コート（クレー）', '第２コート（クレー）')
                                        , new DateTime('2019-09-28 12:30:00'), 1
                                        , 'ハヤシ イクマ'
                                        , '林 郁真'
                                        , '080-5158-7732');
        
        $reservation_units = $reservation_information->get_reservation_units();
        

        $actual_reservation_unit = $reservation_units[0][0];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第１コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 12:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 13:29:59'), $actual_reservation_unit->end_datetime);

        $actual_reservation_unit = $reservation_units[0][1];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第２コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 12:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 13:29:59'), $actual_reservation_unit->end_datetime);
    }
    /**
     * @test
     */
    public function コンストラクタのテスト１(): void //TODO: テスト名の見直し
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
        

        $actual_reservation_unit = $reservation_units[0][0];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第１コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 12:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 14:29:59'), $actual_reservation_unit->end_datetime);
                                     
        $actual_reservation_unit = $reservation_units[0][1];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第２コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 12:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 14:29:59'), $actual_reservation_unit->end_datetime);

        $actual_reservation_unit = $reservation_units[1][0];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第３コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 12:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 14:29:59'), $actual_reservation_unit->end_datetime);

        $this->assertEquals(1, count($reservation_units[1]));

        
        $actual_reservation_unit = $reservation_units[2][0];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第１コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 14:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 16:29:59'), $actual_reservation_unit->end_datetime);
                                     
        $actual_reservation_unit = $reservation_units[2][1];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第２コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 14:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 16:29:59'), $actual_reservation_unit->end_datetime);

        $actual_reservation_unit = $reservation_units[3][0];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第３コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 14:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 16:29:59'), $actual_reservation_unit->end_datetime);

        $this->assertEquals(1, count($reservation_units[3]));


    }

    /**
     * @test
     */
    public function コンストラクタのテスト2(): void //TODO: テスト名の見直し
    {
        $account = new Account('10008771','1125k');
        $reservation_information = new ReservationInformation($account
                                        , '木ノ下テニスコート'
                                        , array('第１コート（クレー）', '第２コート（クレー）', '第３コート（クレー）')
                                        , new DateTime('2019-09-28 12:30:00'), 3
                                        , 'ハヤシ イクマ'
                                        , '林 郁真'
                                        , '080-5158-7732');
        
        $reservation_units = $reservation_information->get_reservation_units();
        

        $actual_reservation_unit = $reservation_units[0][0];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第１コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 12:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 14:29:59'), $actual_reservation_unit->end_datetime);
                                     
        $actual_reservation_unit = $reservation_units[0][1];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第２コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 12:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 14:29:59'), $actual_reservation_unit->end_datetime);

        $actual_reservation_unit = $reservation_units[1][0];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第３コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 12:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 14:29:59'), $actual_reservation_unit->end_datetime);

        $this->assertEquals(1, count($reservation_units[1]));

        
        $actual_reservation_unit = $reservation_units[2][0];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第１コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 14:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 15:29:59'), $actual_reservation_unit->end_datetime);
                                     
        $actual_reservation_unit = $reservation_units[2][1];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第２コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 14:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 15:29:59'), $actual_reservation_unit->end_datetime);

        $actual_reservation_unit = $reservation_units[3][0];
        $this->assertEquals('木ノ下テニスコート', $actual_reservation_unit->facility_name);
        $this->assertEquals('第３コート（クレー）', $actual_reservation_unit->court_name);
        $this->assertEquals(new Datetime('2019-09-28 14:30:00'), $actual_reservation_unit->start_datetime);
        $this->assertEquals(new Datetime('2019-09-28 15:29:59'), $actual_reservation_unit->end_datetime);

        $this->assertEquals(1, count($reservation_units[3]));


    }
}
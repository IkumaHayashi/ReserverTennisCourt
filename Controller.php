<?php
declare(strict_types=1);

require_once './model/Account.php';
require_once './model/BaseLogicOperator.php';
require_once './model/ChromeOperator.php';
require_once './model/FacilityConditionResercher.php';
require_once './model/FacilityReservationConfirmer.php';
require_once './model/ReservationInformation.php';
require_once './model/CourtReserver.php';
require_once './model/CourtLotteryReserver.php';
require_once '../vendor/autoload.php';

//設定ファイル読み込み
$settings = json_decode(file_get_contents('./config/settings.json'), false);
$account = new Account($settings->accounts[0]->id,$settings->accounts[0]->password);

//try{

    //設備の予約状況を取得して配列に格納
    /*
    $court_condition_resercher = new FacilityConditionResercher($settings->server_url,$settings->start_url,$settings->frame_name, new DateTime('2019-09-28'), '境川緑道公園テニスコート');
    $table_values = array();
    $table_values = $court_condition_resercher->get_facility_status();
    $court_condition_resercher = null;
*/
    //アカウント指定して予約状況を取得して配列に格納
    /*
    $reserver_confirmer = new FacilityReservationConfirmer($settings->server_url,$settings->start_url,$settings->frame_name,$account );
    $table_values = array();
    $reserver_confirmer->get_reservation(new DateTime('2019-10-01 00:00:00'), new DateTime('2019-10-31 23:59:59'), $table_values );
    $reserver_confirmer = null;
    foreach ($table_values as $values) fputcsv(STDOUT, $values);
    */
    //予約
    $reservation_information = new ReservationInformation($account, '木ノ下テニスコート', array('第１コート（クレー）', '第２コート（クレー）'), new DateTime('2019-10-12 12:30:00'), 4
                                                         , $settings->representative->kana_name
                                                         , $settings->representative->name
                                                         , $settings->representative->telnumber);
    $court_reserver = new CourtReserver($settings->server_url,$settings->start_url,$settings->frame_name, $reservation_information);
    $court_reserver->reserve();
    $court_reserver = null;
/*
}catch (Exception $e) {
    
    print($e->getMessage());

}finally{
}
*/
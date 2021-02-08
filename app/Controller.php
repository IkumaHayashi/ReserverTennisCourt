<?php
declare(strict_types=1);

require_once './model/Account.php';
require_once './model/BaseLogicOperator.php';
require_once './model/ChromeOperator.php';
require_once './model/FacilityConditionResercher.php';
require_once './model/FacilityReservationConfirmer.php';
require_once './model/ReservationInformation.php';
require_once './model/CourtReserver.php';
require_once './model/CourtLotteror.php';
require_once './vendor/autoload.php';


$server_url = 'http://localhost:4444/wd/hub';
$start_url = 'https://www.e-reserve.jp/eap-rj/rsv_rj/Core_i/init.asp?KLCD=212019&SBT=1&Target=_Top&LCD=';
$frame_name = 'MainFrame';

//try{

    $accounts = [];
    $accounts[] = new Account('10006770','1125'); 
    $accounts[] = new Account('10008771','1125k'); 
    $accounts[] = new Account('10001685','1125'); 
    $accounts[] = new Account('10008751','1125k'); 
    $accounts[] = new Account('10008769','1125k'); 
    $accounts[] = new Account('10009221','kinka51'); 
    $accounts[] = new Account('10009280','a10009280');

    //設備の予約状況を取得して配列に格納
    
    // $court_condition_resercher = new FacilityConditionResercher($server_url,$start_url,$frame_name, new DateTime('2019-09-28'), '境川緑道公園テニスコート');
    // $table_values = array();
    // $table_values = $court_condition_resercher->get_facility_status();
    // var_dump($table_values);
    // $court_condition_resercher = null;
    

    //アカウント指定して予約状況を取得して配列に格納
    // $all_table_values = [];
    // foreach ($accounts as $account) {
    //     $reserver_confirmer = new FacilityReservationConfirmer($server_url,$start_url,$frame_name,$account );
    //     $table_values = [];
    //     $reserver_confirmer->get_reservation(new DateTime('2021-03-01 00:00:00'), new DateTime('2021-03-31 23:59:59'), $table_values );
    //     $all_table_values = array_merge($all_table_values, $table_values);
    //     $reserver_confirmer = null;
    // }
    // $file = fopen("結果.csv", "w");
    // foreach ($all_table_values as $row) {
    //     fputcsv($file, $row);
    // }
    // fclose($file);

    // 予約取り消し

    
    
    //予約
    $account = new Account('10006770','1125'); 
    $reservation_information = new ReservationInformation($account, '岐阜ファミリーパークテニスコート', array('第３コート', '第４コート'), new DateTime('2021-03-13 12:30:00'), 2
         , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    $court_reserver = new CourtReserver($server_url,$start_url,$frame_name, $reservation_information);
    $court_reserver->reserve();
    $court_reserver = null;

    // 抽選
    // foreach ($accounts as $account) {
    //     $reservation_informations[] = new ReservationInformation($account, '岐阜ファミリーパークテニスコート', array('第３コート', '第４コート'), new DateTime('2021-03-13 12:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    //     $reservation_informations[] = new ReservationInformation($account, '岐阜ファミリーパークテニスコート', array('第３コート', '第４コート'), new DateTime('2021-03-13 14:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    //     $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-03-21 12:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    //     $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-03-21 14:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    //     $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ａ面', 'Ｂ面'), new DateTime('2021-03-28 12:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    //     $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ａ面', 'Ｂ面'), new DateTime('2021-03-28 14:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    //     $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ｃ面', 'Ｄ面'), new DateTime('2021-03-28 12:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    //     $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ｃ面', 'Ｄ面'), new DateTime('2021-03-28 14:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    //     $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ａ面', 'Ｂ面'), new DateTime('2021-03-21 12:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    //     $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ａ面', 'Ｂ面'), new DateTime('2021-03-21 14:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    //     $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ｃ面', 'Ｄ面'), new DateTime('2021-03-21 12:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    //     $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ｃ面', 'Ｄ面'), new DateTime('2021-03-21 14:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    //     foreach ($reservation_informations as $reservation_information) {
    //         $court_lotteror = new CourtLotteror($server_url,$start_url,$frame_name, $reservation_information);
    //         $court_lotteror->lottery();
    //         $court_lotteror = null;
    //     }
    //     $reservation_informations = [];
    // }
/*
}catch (Exception $e) {
    
    print($e->getMessage());

}finally{
}
*/
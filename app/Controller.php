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
$start_url = 'https://www.pa-reserve.jp/eap-rj/rsv_rj/core_i/init.asp?KLCD=212019&SBT=1&Target=_Top&LCD=';
$frame_name = 'MainFrame';

//try{

    $accounts = [];
    $accounts[] = new Account('10006770','1125'); 
    $accounts[] = new Account('10001685','1125'); 
    $accounts[] = new Account('10007768','1125k'); 
    $accounts[] = new Account('10008751','1125k'); 
    $accounts[] = new Account('10008771','1125k'); 
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
    //     $reserver_confirmer->get_reservation(new DateTime('2021-05-01 00:00:00'), new DateTime('2021-05-31 23:59:59'), $table_values );
    //     $all_table_values = array_merge($all_table_values, $table_values);
    //     $reserver_confirmer = null;
    // }
    // $file = fopen("result.csv", "w");
    // fwrite($file, arr2csv($all_table_values));
    // fclose($file);

    // 予約取り消し
    // foreach ($accounts as $account) {
    //     $reserver_confirmer = new FacilityReservationConfirmer($server_url,$start_url,$frame_name,$account);
    //     $reserver_confirmer->cancel(new DateTime('2021-05-08'));
    //     $reserver_confirmer = null;
    // }
    // $account = $accounts[4];
    // $reserver_confirmer = new FacilityReservationConfirmer($server_url,$start_url,$frame_name,$account);
    // $reserver_confirmer->cancel(new DateTime('2021-05-30'));
    // $reserver_confirmer = null;
    // $account = $accounts[5];
    // $reserver_confirmer = new FacilityReservationConfirmer($server_url,$start_url,$frame_name,$account);
    // $reserver_confirmer->cancel(new DateTime('2021-05-30'));
    // $reserver_confirmer = null;
    // $account = $accounts[6];
    // $reserver_confirmer = new FacilityReservationConfirmer($server_url,$start_url,$frame_name,$account);
    // $reserver_confirmer->cancel(new DateTime('2021-05-15'));
    // $reserver_confirmer = null;
    
    
    //予約
    $account = $accounts[0];
    $reservation_infromations = [];
    $reservation_infromations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）','第７コート（人工芝）'), new DateTime('2021-05-15 12:30:00'), 2
        , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    // $reservation_infromations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第１コート（クレー）','第２コート（クレー）'), new DateTime('2021-05-23 12:30:00'), 2
    //         , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    // $reservation_infromations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第１コート（クレー）','第２コート（クレー）'), new DateTime('2021-05-23 14:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    // $reservation_infromations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第１コート（クレー）','第２コート（クレー）'), new DateTime('2021-05-29 12:30:00'), 2
    //         , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    // $reservation_infromations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第１コート（クレー）','第２コート（クレー）'), new DateTime('2021-05-29 14:30:00'), 2
    //     , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    foreach( $reservation_infromations as $reservation_information)
    {
        $court_reserver = new CourtReserver($server_url,$start_url,$frame_name, $reservation_information);
        $court_reserver->reserve();
        $court_reserver = null;
    }

    // 抽選
    // foreach ($accounts as $account) {
        // $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-05-01 12:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-05-01 14:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-05-02 12:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-05-02 14:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-05-03 12:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-05-03 14:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '岐阜ファミリーパークテニスコート', array('第１コート', '第２コート'), new DateTime('2021-05-04 12:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '岐阜ファミリーパークテニスコート', array('第１コート', '第２コート'), new DateTime('2021-05-04 14:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '岐阜ファミリーパークテニスコート', array('第１コート', '第２コート'), new DateTime('2021-05-05 12:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '岐阜ファミリーパークテニスコート', array('第１コート', '第２コート'), new DateTime('2021-05-05 14:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ａ面', 'Ｂ面'), new DateTime('2021-05-08 12:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ａ面', 'Ｂ面'), new DateTime('2021-05-08 14:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ａ面', 'Ｂ面'), new DateTime('2021-05-09 12:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ａ面', 'Ｂ面'), new DateTime('2021-05-09 14:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-05-15 12:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-05-15 14:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-05-16 12:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-05-16 14:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ｃ面', 'Ｄ面'), new DateTime('2021-05-22 12:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ｃ面', 'Ｄ面'), new DateTime('2021-05-22 14:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ａ面', 'Ｂ面'), new DateTime('2021-05-23 12:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ａ面', 'Ｂ面'), new DateTime('2021-05-23 14:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ａ面', 'Ｂ面'), new DateTime('2021-05-29 12:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '境川緑道公園テニスコート', array('Ａ面', 'Ｂ面'), new DateTime('2021-05-29 14:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
        // $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-05-30 12:30:00'), 2
        // , 'タナカ ツカサ', '田中 僚', '090-8688-6773');
    //     $reservation_informations[] = new ReservationInformation($account, '木ノ下テニスコート', array('第６コート（人工芝）', '第７コート（人工芝）'), new DateTime('2021-05-30 14:30:00'), 2
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
function arr2csv($fields) {
    $fp = fopen('php://temp', 'r+b');
    foreach($fields as $field) {
        fputcsv($fp, $field);
    }
    rewind($fp);
    $tmp = str_replace(PHP_EOL, "\r\n", stream_get_contents($fp));
    return mb_convert_encoding($tmp, 'SJIS-win', 'UTF-8');
}
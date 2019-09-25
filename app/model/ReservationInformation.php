<?php
require_once './model/ReservationUnit.php';

class ReservationInformation
{
    public $account;
    public $facility_name;
    public $court_names;
    public $start_datetime;
    public $duration_hour;
    public $representative_kana_name;
    public $representative_name;
    public $representative_telnumber;
    public $reserved_datetime;

    private $reservation_units;

    public function __construct($_account, string $_facility_name, $_court_names
                                , $_start_datetime, int $_duration_hour
                                , string $_representative_kana_name, string $_representative_name
                                , string $_representative_telnumber)
    {

        //値チェック
        if($_duration_hour <= 0){
            throw new \LogicException('$_duration_hourは1以上の値を指定してください。');
        };

        $this->account = $_account;
        $this->facility_name = $_facility_name;
        $this->court_names = $_court_names;
        $this->start_datetime = $_start_datetime;
        $this->duration_hour = $_duration_hour;
        $this->representative_kana_name = $_representative_kana_name;
        $this->representative_name = $_representative_name;
        $this->representative_telnumber = $_representative_telnumber;
        $this->reserved_datetime = clone $_start_datetime;

        $this->set_reservation_units();

    }

    public function get_start_date_texts()
    {
        return explode(' ', $this->start_datetime->format('Y n j'));
    }

    public function get_end_datetime()
    {
        $end_datetime = clone $this->start_datetime;
        return $end_datetime->modify('+'.$this->duration_hour.' hour');
    }

    public function set_reserved_datetime(int $reserved_duration_hour){
        $this->reserved_datetime->modify('+' . ($reserved_duration_hour * 60) . ' minutes');
    }

    /**
     * 予約対象範囲を1回の予約可能単位である1コートあたり2コマ、最大4コマで設定する。
     * ex. 8:30から4時間、第1コート、第2コート、第3コートを指定された場合、下記のように設定する。
     * [0] => ('第1コート', 08:30), ('第1コート', 09:30), ('第2コート', 08:30), ('第2コート', 09:30)
     * [1] => ('第1コート', 10:30), ('第1コート', 11:30), ('第2コート', 10:30), ('第2コート', 11:30)
     * [2] => ('第3コート', 08:30), ('第3コート', 09:30)
     * [3] => ('第3コート', 100:30), ('第3コート', 11:30)
     */
    private function set_reservation_units(){

        $this->reservation_units = array();


        $interval_hour = $this->start_datetime->diff($this->get_end_datetime())->h;

        for($i = 0; $i < ceil($interval_hour / 2); $i++ ){
            

            $reserve_start_datetime = clone($this->start_datetime);
            $reserve_start_datetime = $reserve_start_datetime->modify('+'.($i * 2).' hours');

            if( ($i + 1) < ceil($interval_hour / 2)){
                $reserve_end_datetime = clone($reserve_start_datetime);
                $reserve_end_datetime = $reserve_end_datetime->modify('+2 hours')->modify('-1 seconds');
            }else if( ($interval_hour % 2) == 0 ){
                $reserve_end_datetime = clone($reserve_start_datetime);
                $reserve_end_datetime = $reserve_end_datetime->modify('+2 hours')->modify('-1 seconds');
            }else{
                $reserve_end_datetime = clone($reserve_start_datetime);
                $reserve_end_datetime = $reserve_end_datetime->modify('+1 hours')->modify('-1 seconds');
            }

            for($j = 0; $j < ceil(count($this->court_names)) / 2; $j++){

                $reservation_unit_pair = array();

                $reservation_unit = new ReservationUnit($this->facility_name, $this->court_names[$j * 2], $reserve_start_datetime, $reserve_end_datetime);
                $reservation_unit_pair[0] = $reservation_unit;

                if( ($j * 2 + 1) < count($this->court_names) ){
                    $reservation_unit = new ReservationUnit($this->facility_name, $this->court_names[$j * 2 + 1], $reserve_start_datetime, $reserve_end_datetime);
                    $reservation_unit_pair[1] = $reservation_unit;
                }

                $this->reservation_units[count($this->reservation_units)] = $reservation_unit_pair;
                
            }

        }
    }

    public function get_reservation_units(int $index = -1)
    {
        return $this->reservation_units;
        if($index !== -1 ){
            return $this->reservation_units;
        }else{
            return $this->reservation_units[$index];
        }
    }

}
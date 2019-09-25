<?php
require_once './model/BaseLogicOperator.php';

use Facebook\WebDriver\WebDriverBy;

/**
 * 設備の予約状況を取得する
 */
Class FacilityConditionResercher extends BaseLogicOperator
{

    protected $target_date;
    protected $facility_name;

    public function __construct(string $selenium_server_url, string $start_url, string $frame_name, $_target_date, string $_favility_name)
    {
        parent::__construct($selenium_server_url, $start_url, $frame_name);
        $this->target_date = explode(" ", $_target_date->format('Y n j'));
        $this->facility_name = $_favility_name;
    }

    
    public function get_facility_status()
    {
        //TOPページ遷移
        //$this->navigate_top_page();

        //対象コート、日付のページへ遷移
        $this->navigate_target_facility_page($this->facility_name, $this->target_date);
        $this->navigate_to_target_date($this->target_date);
    
        return $this->get_reservation_table_to_array();
        
    }

    private function get_court_status_from_td($td): string
    {

        //imgタグを取得
        try{
            $img = $td->findElement(WebDriverBy::tagName('img'));
        }catch(Exception $ex){}
        
        //取得したimgからコート状況を判断。imgがない場合は時間外とする。
        if(isset($img))
        {
            return $img->getAttribute('alt');
        }else{
            return "時間外";
        }
    }

    private function get_reservation_table_to_array()
    {
        
        //開始時間を取得
        $start_datetime = $this->get_facility_start_datetime();
        
        //設備状況のテーブルを取得
        $table = $this->get_element_by_xpath('/html/body/form/div[2]/div[2]/left/left/table[3]');
        $trs = $table->findElements(WebDriverBy::tagName('tr'));
        
        //各コートごとに状況を取得
        $facility_status = array();
        for($i = 4; $i < count($trs); $i++){
            $tr = $trs[$i];

            //コート名取得
            $th = $tr->findElement(WebDriverBy::tagName('th'));
            $court_name = $th->getText();
        
            //各時間の予約状況取得
            $tds = $tr->findElements(WebDriverBy::tagName('td'));
            $time = clone $start_datetime;
            $court_status_array = array();
            for($j = 0; $j < count($tds); $j++){

                //時間間隔を取得
                $time_span = (int)$tds[$j]->getAttribute('colspan');

                $status = $this->get_court_status_from_td($tds[$j]);

                //15分刻みで状況を配列に格納
                for($k = 0; $k < $time_span; $k++){
                    $court_status_array[$time->format('Y-m-d H:i')] = $status;
                    $time->modify('+15 minutes');
                }
            }
            $facility_status[$court_name] = $court_status_array;
        }

        return $facility_status;
    }

}

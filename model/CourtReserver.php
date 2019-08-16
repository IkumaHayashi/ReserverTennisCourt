<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriver;

require_once './model/ReservationInformation.php';

/**
 * コート予約確認を行う
 */
Class CourtReserver extends BaseLogicOperator
{
    private $reservation_information;

    public function __construct(string $selenium_server_url, string $start_url, string $frame_name, $reservation_information)
    {
        parent::__construct($selenium_server_url, $start_url, $frame_name);
        $this->reservation_information = $reservation_information;
    }

    public function reserve()
    {
        //ログイン後、トップページへ遷移
        $this->login($this->reservation_information->account);
        $this->navigate_top_page();

        //対象の設備ページ・日付へ移動
        $this->navigate_target_facility_page($this->reservation_information->facility_name);
        $this->navigate_to_target_date($this->reservation_information->start_datetime);

        //対象の予約コマをクリック
        $this->click_reserve_koma();
    }

    private function click_reserve_koma()
    {

        //施設の開始時間を取得
        /*
        $start_datetime = $this->get_facility_start_datetime();
        printf("start %s \n", $start_datetime->format('Y-m-d H:i:s')."\n");
        $count_datetime = clone $start_datetime;
        printf("count %s \n", $count_datetime->format('Y-m-d H:i:s')."\n");
        $end_datetime = clone $start_datetime;
        $end_datetime = $start_datetime->modify('+'.$this->reservation_information->duration_hour.' hours');
        printf("end %s \n", $end_datetime->format('Y-m-d H:i:s')."\n");
            */

        //すべてのコートの該当コマを取得
        for($i = 0; $i < count($this->reservation_information->court_names); $i++)
        {
            
            //施設の開始時間を取得
            $count_datetime = $this->get_facility_start_datetime();

            //該当コートの行tr,tdを取得
            $tr_element = $this->get_court_name_tr_element($this->reservation_information->court_names[$i]);
            $td_elements = $tr_element->findElements(WebDriverBy::tagName('td'));

            //対象のコマに対し予約対象であれば配列に格納
            for($j = 0; $j < count($td_elements); $j++){



                $koma_duration = $td_elements[$j]->getAttribute('colspan');
                $count_datetime->modify('+'.strval($koma_duration * 15).' minute');
    
                if($td_elements[$j]->getAttribute('class') == 'clsKomaBlank')
                    continue;

                /*
                if($tds[$i]->getAttribute('class') == 'clsKomaBlank')
                    continue;
                    */
    
    
            }
            
        }


    }

    private function get_court_name_tr_element(string $court_name)
    {
        //コート名が記載されているthタグを取得
        $th_tag_elements = $this->get_elements_by_tag_class_and_text('/html/body/form/div[2]/div[2]/left/left/table[3]','th','clsShisetuTitleOneDay', $court_name);
        if($th_tag_elements == null || count($th_tag_elements) == 0 || count($th_tag_elements) > 1){
            throw new \RuntimeException('コート名に対応するth要素が0件もしくは2件以上見つかりました。');
        }

        //親の要素を取得
        $tr_element = $th_tag_elements[0]->findElement(WebDriverBy::xpath('..'));

        return $tr_element;
    }


}

<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriver;

require_once './model/ReservationInformation.php';

/**
 * コート予約・抽選のベース
 */
Class CourtReserverBase extends BaseLogicOperator
{
    protected $reservation_information;

    public function __construct(string $selenium_server_url, string $start_url, string $frame_name, $reservation_information)
    {
        parent::__construct($selenium_server_url, $start_url, $frame_name);
        $this->reservation_information = $reservation_information;
    }

    protected function reserve_base(bool $is_reserve)
    {
        //ログイン後、トップページへ遷移
        $this->login($this->reservation_information->account);

        //対象の設備ページ・日付へ移動
        $this->navigate_target_facility_page($this->reservation_information->facility_name, $is_reserve);
        $this->navigate_to_target_date($this->reservation_information->get_start_date_texts());

        //分割した予約情報をもとに予約を実行
        $reservation_units = $this->reservation_information->get_reservation_units();
        for($i = 0; $i < count($reservation_units); $i++){
            
            //対象の予約コマを取得
            $reserve_target_komas = $this->get_reserve_koma($reservation_units[$i]);
            
            $this->reserve_per_unit($reserve_target_komas);
        }

    }
    protected function get_reserve_koma($reservation_unit_pair)
    {
        $reserve_target_komas = array();

        //すべてのコートの該当コマを取得
        for($i = 0; $i < count($reservation_unit_pair); $i++)
        {
            
            //対象コートの行tr,tdを取得
            $tr_element = $this->get_court_name_tr_element($reservation_unit_pair[$i]->court_name);
            $td_elements = $tr_element->findElements(WebDriverBy::tagName('td'));

            //施設の開始時間を取得
            $count_datetime = $this->get_facility_start_datetime();
            //1コマずつ時間に該当するかどうかチェック
            for($j = 0; $j < count($td_elements); $j++)
            {
                
                if($td_elements[$j]->getAttribute('class') != 'clsKomaBlank'
                    && $count_datetime >= $reservation_unit_pair[$i]->start_datetime
                    && $count_datetime < $reservation_unit_pair[$i]->end_datetime){

                    $reserve_target_komas[count($reserve_target_komas)] = $td_elements[$j];
                
                }

                $koma_duration = $td_elements[$j]->getAttribute('colspan');
                $count_datetime = $count_datetime->modify('+'.strval($koma_duration * 15).' minute');
                
            }

        }

        return $reserve_target_komas;
    }

    protected function reserve_per_unit($reserve_target_komas)
    {

        //対象のコマをクリック
        for($i = 0; $i < count($reserve_target_komas); $i++)
        {
            $reserve_target_komas[$i]->click();
        }

        //次へボタンをクリック
        $this->click_by_xpath('/html/body/form/div[2]/div[2]/left/left/table[2]/tbody/tr[2]/td/input[1]');
        
        //責任者情報を入力
        $element = $this->get_element_by_xpath('/html/body/form/div[2]/div/table/tbody/tr/td/table/tbody/tr[6]/td/input');
        $this->rewrite_text_field($element,$this->reservation_information->representative_kana_name);
        $element = $this->get_element_by_xpath('/html/body/form/div[2]/div/table/tbody/tr/td/table/tbody/tr[7]/td/input');
        $this->rewrite_text_field($element,$this->reservation_information->representative_name);
        $element = $this->get_element_by_xpath('/html/body/form/div[2]/div/table/tbody/tr/td/table/tbody/tr[8]/td/input');
        $this->rewrite_text_field($element,$this->reservation_information->representative_telnumber);
        
        //次へボタンクリック
        $this->click_by_xpath('/html/body/form/div[2]/table[3]/tbody/tr/td[2]/input[1]');

        //利用目的小分類からソフトテニスをクリック
        $this->select_by_visible_text('/html/body/form/div[2]/div/table/tbody/tr[2]/td[5]/select[2]', '04：ソフトテニス');

        //次へボタンクリック
        $this->click_by_xpath('/html/body/form/div[2]/table[3]/tbody/tr/td[2]/table/tbody/tr[2]/td/input');

        //予約ボタンをクリック
        $this->click_by_xpath('/html/body/form/div[2]/center/center/input[1]');
        $dialog = $this->_driver->switchTo()->alert();
        $dialog->accept();
        $dialog = $this->_driver->switchTo()->alert();
        $dialog->accept();

        //施設画面へ戻る
        $this->click_by_xpath('/html/body/form/div[2]/center/table[2]/tbody/tr[2]/td[2]/input');

    }

    protected function get_court_name_tr_element(string $court_name)
    {
        //コート名が記載されているthタグを取得
        $th_tag_elements = $this->get_elements_by_tag_class_and_text('/html/body/form/div[2]/div[2]/left','th','clsShisetuTitleOneDay', $court_name);
        if($th_tag_elements == null || count($th_tag_elements) == 0 || count($th_tag_elements) > 1){
            throw new \RuntimeException('コート名に対応するth要素が0件もしくは2件以上見つかりました。');
        }

        //親の要素を取得
        $tr_element = $th_tag_elements[0]->findElement(WebDriverBy::xpath('..'));

        return $tr_element;
    }


}

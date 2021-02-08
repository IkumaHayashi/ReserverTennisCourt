<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriver;

require_once './model/ReservationInformation.php';

/**
 * コート予約確認を行う
 */
Class CourtReserver extends BaseLogicOperator
{
    protected $reservation_information;

    public function __construct(string $selenium_server_url, string $start_url, string $frame_name, $reservation_information)
    {
        parent::__construct($selenium_server_url, $start_url, $frame_name);
        $this->reservation_information = $reservation_information;
    }

    public function reserve()
    {
        //ログイン後、トップページへ遷移
        $this->login($this->reservation_information->account);

        //対象の設備ページ・日付へ移動
        $this->navigate_target_facility_page($this->reservation_information->facility_name, true);
        $this->navigate_to_target_date($this->reservation_information->get_start_date_texts());

        //1コート当たり2コマ、最大4コマずつ再帰的に実行
        $this->reserve_recursive();
    }

    protected function reserve_recursive()
    {

        //対象の予約コマを取得
        $reserve_target_komas = array();
        $this->get_reserve_koma($reserve_target_komas);

        //予約コマがまだ存在すれば処理を再帰的に実行
        if(count($reserve_target_komas) > 0){
            $this->click_reserve_koma($reserve_target_komas);
            //$this->reserve_recursive();
        }else{
            return;
        }
        
    }

    private function get_reserve_koma(&$reserve_target_komas)
    {

        //予約範囲の時間を取得
        $from_datetime = $this->reservation_information->reserved_datetime;
        $end_datetime = $this->reservation_information->get_end_datetime();
        $interval = $from_datetime->diff($end_datetime);

        $interval_hour = intval($interval->format('%h'));
        $to_datetime = clone $from_datetime;
        switch ($interval_hour) {
            case 0:
                return;
            case 1:
                $to_datetime->modify('+1 hour');
            default:
                $to_datetime->modify('+2 hour');
                break;
        }

        //すべてのコートの該当コマを取得
        for($i = 0; $i < count($this->reservation_information->court_names); $i++)
        {
            
            //該当コートの行tr,tdを取得
            $tr_element = $this->get_court_name_tr_element($this->reservation_information->court_names[$i]);
            $td_elements = null;
            $td_elements = $tr_element->findElements(WebDriverBy::tagName('td'));
            $court_name = $tr_element->getText();
            
            //施設の開始時間を取得
            $count_datetime = $this->get_facility_start_datetime();
            $reserve_court_target_komas = array(); 
            for($j = 0; $j < count($td_elements); $j++)
            {
                if($td_elements[$j]->getAttribute('class') != 'clsKomaBlank'
                    && $count_datetime >= $from_datetime
                    && $count_datetime < $to_datetime){

                    $reserve_court_target_komas[count($reserve_court_target_komas)] = $td_elements[$j];
                
                }

                $koma_duration = $td_elements[$j]->getAttribute('colspan');
                $count_datetime = $count_datetime->modify('+'.strval($koma_duration * 15).' minute');
                
            }
            $reserve_target_komas[$court_name] = $reserve_court_target_komas;
        }
    }

    private function click_reserve_koma(&$reserve_target_komas)
    {
        //取得した対象のコマをカウント
        $target_koma_count = 0;
        foreach ($reserve_target_komas as $court_name => $reserve_court_target_komas) {
            foreach ($reserve_court_target_komas as $koma) {
                $target_koma_count++;
            }
        }

        //予約対象コマがなければ処理を終了
        printf("target_koma_count is %d\n",$target_koma_count);
        if($target_koma_count == 0){
            return;
        }
        


        //取得したコマを1コートあたり2コマ、最大4コマずつクリック
        $reserved_count = 1;
        while($reserved_count <= 4){
            
            //コートごとに配列を取り出し
            foreach ($reserve_target_komas as $court_name => $reserve_court_target_komas) {

                //1回あたり4コマの上限に達したら抜ける
                if($reserved_count > 4){
                    break;
                }

                //1コートあたり2コマまでクリック
                $count_per_court = 0;
                foreach ($reserve_court_target_komas as $index => $koma) {
                    
                    if($count_per_court > 1){
                        break;
                    }else{
                        printf("click ! courtname = $court_name, index = $index, %d,%d\n", $koma->getLocation()->getX(),$koma->getLocation()->getY());
                        $koma->click();
                        $count_per_court++;
                        $reserved_count++;
                        unset(($reserve_target_komas[$court_name])[$index]);
                    }

                }

            }
        }

        //次へボタンをクリック
        $this->click_by_xpath('/html/body/form/div[2]/div[2]/left/table[2]/tbody/tr[2]/td/input[1]');
        
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
        $this->click_by_xpath('/html/body/form/div[2]/table[3]/tbody/tr[1]/td[2]/input');

        //予約ボタンをクリック
        $this->click_by_xpath('/html/body/form/div[2]/center/center/input[1]');
        $dialog = $this->_driver->switchTo()->alert();
        $dialog->accept();

        // TODO: 抽選時と予約時でわける？
        //$dialog = $this->_driver->switchTo()->alert();
        // $dialog->accept();
        //var_dump("switch_main_frome;");

        //施設画面へ戻る
        $this->click_by_xpath('/html/body/form/div[2]/center/table[2]/tbody/tr[2]/td[2]/input');

        //再帰的に呼び出し
        // TODO:一旦再帰的にやるのをやめる
        //$this->click_reserve_koma($reserve_target_komas);

        //$element->te
        //取得したコマを1コートあたり2コマ、最大4コマずつクリック
        /*for($i = 0; $i < ceil(count($reserve_target_komas) / 4); $i++)
        {
            for($j = 0; $j < 4; $j++)
            {
                if( ($i * 4 + $j) > count($reserve_target_komas))
                {
                    break;
                }
                $reserve_target_komas[$i * 4 + $j]->click();
            }

            //次へボタンをクリック
            $this->click_by_xpath('/html/body/form/div[2]/div[2]/left/left/table[2]/tbody/tr[2]/td/input[1]');
            
            //責任者情報を入力
            $element = $this->get_element_by_xpath('/html/body/form/div[2]/div/table/tbody/tr/td/table/tbody/tr[6]/td/input');
            //$element->te

        }*/
    }

    private function get_court_name_tr_element(string $court_name)
    {
        //コート名が記載されているthタグを取得
        $th_tag_elements = $this->get_elements_by_tag_class_and_text('/html/body/form/div[2]/div[2]/left/table[3]/tbody/tr/td/table','th','clsShisetuTitleOneDay', $court_name);
        if($th_tag_elements == null || count($th_tag_elements) == 0 || count($th_tag_elements) > 1){
            throw new \RuntimeException('コート名に対応するth要素が0件もしくは2件以上見つかりました。');
        }

        //親の要素を取得
        $tr_element = $th_tag_elements[0]->findElement(WebDriverBy::xpath('..'));
        return $tr_element;
    }


}


<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriver;


/**
 * コート予約確認を行う
 */
Class CourtLotteror extends CourtReserver
{
    public function lottery()
    {
        //ログイン後、トップページへ遷移
        $this->login($this->reservation_information->account);

        //対象の設備ページ・日付へ移動
        $this->navigate_target_facility_page($this->reservation_information->facility_name, false);
        $this->navigate_to_target_date($this->reservation_information->get_start_date_texts());

        //1コート当たり2コマ、最大4コマずつ再帰的に実行
        $this->reserve_recursive();
    }

}

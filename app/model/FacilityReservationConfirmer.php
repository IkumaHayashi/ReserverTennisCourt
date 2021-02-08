<?php

/**
 * コート予約確認を行う
 */
Class FacilityReservationConfirmer extends BaseLogicOperator
{
    private $account;

    public function __construct(string $selenium_server_url, string $start_url, string $frame_name, $_account)
    {
        $this->account = $_account;
        parent::__construct($selenium_server_url, $start_url, $frame_name);
    }

    private function display_reservation_table($from_date, $to_date)
    {
        $this->click_by_xpath('//*[@id="contColumnB"]/div/p[1]/input');
        $this->select_by_visible_text('/html/body/form/div[2]/center/table[2]/tbody/tr[1]/td/select[1]', $from_date->format('Y年'));
        $this->select_by_visible_text('/html/body/form/div[2]/center/table[2]/tbody/tr[1]/td/select[2]', $from_date->format('n月'));
        $this->select_by_visible_text('/html/body/form/div[2]/center/table[2]/tbody/tr[1]/td/select[3]', $from_date->format('j日'));
        $this->select_by_visible_text('/html/body/form/div[2]/center/table[2]/tbody/tr[1]/td/select[4]', $to_date->format('Y年'));
        $this->select_by_visible_text('/html/body/form/div[2]/center/table[2]/tbody/tr[1]/td/select[5]', $to_date->format('n月'));
        $this->select_by_visible_text('/html/body/form/div[2]/center/table[2]/tbody/tr[1]/td/select[6]', $to_date->format('j日'));
        $this->click_by_xpath('/html/body/form/div[2]/center/p/input[1]');
    }

    public function get_reservation($from_date, $to_date, &$table_values)
    {
        $this->login($this->account);
        $this->display_reservation_table($from_date, $to_date);
        $this->store_table_to_array('/html/body/form/div[2]/center/div/table[2]', $table_values);
        foreach ($table_values as $index => $row) {
            if ($index === 0) {
                array_unshift($row, "アカウント名");
            } else {
                array_unshift($row, $this->account->id);
            }
            $table_values[$index] = $row;
        }
    }
    
}

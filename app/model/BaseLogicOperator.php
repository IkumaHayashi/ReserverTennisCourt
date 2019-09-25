<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

require_once './model/ChromeOperator.php';

Class BaseLogicOperator extends ChromeOperator
{

    protected function login($account)
    {
        $this->click_by_xpath('//*[@id="contColumnB"]/div/p[1]/input');
        $this->waitUntilNextElementDisplayed(WebDriverBy::xpath('/html/body/form/div[2]/center/table[3]/tbody/tr[1]/td/input'));
        $this->input_text_by_xpath('/html/body/form/div[2]/center/table[3]/tbody/tr[1]/td/input', $account->id);
        $this->input_text_by_xpath('/html/body/form/div[2]/center/table[3]/tbody/tr[2]/td/input', $account->password);
        $this->click_by_xpath('/html/body/form/div[2]/center/p/input[1]');
        $this->click_by_xpath('//*[@id="btnGoTop"]/input');
    }

    protected function navigate_target_facility_page($facility_name)
    {
        $this->_driver->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath('//*[@id="contColumnA"]/div[2]/p[1]/input'))
        );
        echo("[navigate_target_facility_page] 目的画像をクリック\n");
        $this->click_by_xpath('//*[@id="contColumnA"]/div[2]/p[1]/input');

        
        $this->_driver->wait()->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath('//*[@id="000040"]'))
        );
        //$this->waitUntilNextElementDisplayed(WebDriverBy::xpath('//*[@id="000040"]'));
        $this->click_by_xpath('//*[@id="000040"]');
        $this->waitUntilNextElementDisplayed(WebDriverBy::xpath('/html/body/form/div[2]/center/table[2]/tbody/tr/td[3]/div/input[1]'));

        $this->click_by_xpath('/html/body/form/div[2]/center/table[2]/tbody/tr/td[3]/div/input[1]');
        $this->click_by_xpath('/html/body/form/div[2]/center/table[4]/tbody/tr/td/table/tbody/tr[1]/td[2]/input');
        $this->select_by_visible_text('/html/body/form/div[2]/div[2]/left/table/tbody/tr[1]/td/select', $facility_name);
        
    }

    
    protected function get_facility_start_datetime()
    {
        //日付を取得
        $date_th_element = $this->_driver->findElement(WebDriverBy::xpath('/html/body/form/div[2]/div[2]/left/left/table[3]/tbody/tr[1]/td[2]/table/tbody/tr/th[2]'));
        $date_text = preg_replace("/（.+?）/", "",$date_th_element->getText());

        //時間を取得
        $start_time_td_element = $this->_driver->findElement(WebDriverBy::xpath('/html/body/form/div[2]/div[2]/left/left/table[3]/tbody/tr[2]/td[1]'));
        $time_text = $start_time_td_element->getText();
        
        //DateTime型にして返す
        $start_datetime = DateTime::createFromFormat('Y年n月j日 G:i:s', $date_text." ".$time_text.":00");
        return $start_datetime;
    }

    protected function navigate_to_target_date($target_date_texts)
    {

        //年を移動
        $target_elements = $this->get_elements_by_tag_class_and_text('/html/body/form/div[2]/div[1]/center/table[2]', 'a', '', ltrim($target_date_texts[0], '0'));
        if(count($target_elements) == 1){
            $target_elements[0]->click();
        }

        //月を移動
        $target_elements = $this->get_elements_by_tag_class_and_text('/html/body/form/div[2]/div[1]/center/table[3]', 'a', '', ltrim($target_date_texts[1], '0'));
        if(count($target_elements) == 1){
            $target_elements[0]->click();
        }
        //日を移動
        $target_elements = $this->get_elements_by_tag_class_and_text('/html/body/form/div[2]/div[1]/center/table[4]', 'a', '', ltrim($target_date_texts[2], '0'));
        if(count($target_elements) == 1){
            $target_elements[0]->click();
        }

    }

}
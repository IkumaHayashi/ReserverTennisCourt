<?php
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverSelect;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;

/**
 * Chrome操作を行う
 */
class ChromeOperator
{
    protected $_selenium_server_url;
    protected $_driver;
    protected $_start_url;
    protected $_frame_name;

    public function __construct(string $selenium_server_url, string $start_url, string $frame_name)
    {
        putenv('webdriver.chrome.driver=/usr/local/bin/chromedriver');

        $this->_selenium_server_url = $selenium_server_url;
        $this->_start_url = $start_url;
        $this->_frame_name = $frame_name;

        $options = new ChromeOptions();
        $options->addArguments(['--no-sandbox']);

        $caps = DesiredCapabilities::chrome();
        $caps->setCapability(ChromeOptions::CAPABILITY, $options);
        $this->_driver = ChromeDriver::start($caps);
        
        $this->navigate_top_page();
    }

    protected function get_title()
    {
        return $this->_driver->getTitle();
    }

    protected function navigate_top_page(){
        $this->navigate($this->_start_url);
    }

    protected function navigate($url){
        $this->_driver->get($url);
        $this->switch_main_frome();
    }

    protected function switch_main_frome()
    {
        if(isset($this->_frame_name) && $this->_frame_name !== ''){
            $this->switch_frame($this->_frame_name);
        }
    }

    protected function switch_frame($framename)
    {
        $this->_driver->switchTo()->frame($framename);
    }

    protected function get_elements_by_name($name)
    {
        return $this->_driver->findElements(WebDriverBy::name($name));
    }

    protected function get_element_by_id($id)
    {
        echo("[get_element_by_id] id = $id\n");
        return $this->_driver->findElement(WebDriverBy::id($id));
    }

    protected function get_element_by_xpath($xpath)
    {
        return $this->_driver->findElement(WebDriverBy::xpath($xpath));
    }

    protected function click_by_xpath($xpath)
    {
        $element = $this->get_element_by_xpath($xpath);
        $element->click();
    }

    protected function waitUntilNextElementDisplayed(WebDriverBy $webDriverBy)
    {
        echo("[waitUntilNextElementDisplayed] START");
        $this->_driver->wait(10, 500)->until(
            WebDriverExpectedCondition::presenceOfElementLocated($webDriverBy)
        );
    }

    protected function get_elements_by_tag_class_and_text(string $parent_element_xpath, string $tag_name, string $class_name, string $text)
    {

        $parent_element = $this->get_element_by_xpath($parent_element_xpath);

        $elements = $parent_element->findElements(WebDriverBy::tagName($tag_name));
        
        // クラス名で絞り込み
        if($class_name !== ""){

            $tmp_elements = array();
            foreach ($elements as $element) {
                
                if(strpos($element->getAttribute('class'), $class_name) !== false){
                    array_push($tmp_elements, $element);
                }
            }
            $elements = $tmp_elements;
            
        }
        // テキストを含むエレメントを絞り込み
        if($text !== ""){
            $tmp_elements = array();
            foreach ($elements as $element) {
                if(strpos($element->getText(), $text) !== false){
                    array_push($tmp_elements, $element);
                }
            }
            $elements = $tmp_elements;
        }

        return $elements;

    }

    /**
     * 一致するクラス名、テキストをクリック。なければエラーを投げる。
     */
    protected function click_element_by_tag_class_and_text(string $parent_element_xpath, string $tag_name, string $class_name, string $text)
    {

        $target_elements = $this->get_elements_by_tag_class_and_text($parent_element_xpath, $tag_name, $class_name, $text);
        foreach ($target_elements as $element) {
            if ($element->getText() === $text) {
                $element->click();
                return;
            }
        }
        throw new \RuntimeException('対象のDOMが取得できない もしくは 複数件見つかりました。');

    }

    protected function select_by_visible_text($select_xpath, $value)
    {
        $select = new WebDriverSelect($this->get_element_by_xpath($select_xpath));
        $select->selectByVisibleText($value);
    }

    protected function input_text_by_xpath($xpath, $inputed_text)
    {
        $element = $this->get_element_by_xpath($xpath);
        $element->sendKeys($inputed_text);
    }

    protected function take_screenshot($filepath){
        $this->_driver->takeScreenshot($filepath);
    }

    protected function store_table_to_array($table_xpath, &$table_values){

        $table = $this->get_element_by_xpath($table_xpath);

        $trs = $table->findElements(WebDriverBy::tagName('tr'));
        

        //1行目を取得
        $index = 0;
        $ths = $trs[0]->findElements(WebDriverBy::tagName('th'));
        for($i = 0; $i < count($ths); $i++)
        {
            $row_values[] = str_replace(array("\r\n", "\r", "\n","<br>"),' ',$ths[$i]->getText());
        }
        $table_values = array($index++ => $row_values);

        //2行目以降を取得
        for($i = 1; $i < count($trs); $i++)
        {
            //1行分のすべてのtd要素を取得
            $tds = $trs[$i]->findElements(WebDriverBy::tagName('td'));
            $prev_tds = $trs[$i-1]->findElements(WebDriverBy::tagName('td'));
            if(count($tds) == 0) continue;

            //改行コードをスペースに置換して連想配列に格納
            $row_values = array();
            for($j = 0; $j < count($tds); $j++){
                $row_values[] = str_replace(array("\r\n", "\r", "\n","<br>"),' ',$tds[$j]->getText());
            }
            
            //前行が結合セルであれば、同じ列数に値を同じ列となるように追加
            if(count($prev_tds) == 0) continue;
            for($j = 0; $j < count($prev_tds); $j++){
                $rowspan_value = $prev_tds[$j]->getAttribute('rowspan');
                
                if($rowspan_value === "2"){
                    array_splice($row_values, $j, 0, str_replace(array("\r\n", "\r", "\n","<br>"),' ',$prev_tds[$j]->getText()));
                }
                
            }
             
            $table_values = array_merge($table_values, array($index++ => $row_values));

        }
        
    }


    protected function rewrite_text_field($text_field_element, $text)
    {
        $text_field_element->clear();
        $text_field_element->click();
        $this->_driver->getKeyboard()->sendKeys($text);

    }

    public function __destruct()
    {
        $this->_driver->close();
    }

}

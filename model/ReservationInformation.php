<?php
class ReservationInformation
{
    public $account;
    public $facility_name;
    public $court_names;
    public $start_datetime;
    public $duration_hour;

    public function __construct($_account, string $_facility_name, $_court_names, $_start_datetime, int $_duration_hour)
    {

        //型チェック
        if(gettype($_start_datetime) !== "array")
            throw new \LogicException('$_start_datetimeの引数が不正です。');

        if(count($_start_datetime) !== 3)
            throw new \LogicException('$_start_datetimeの引数が不正です。');

        if(gettype($_start_datetime[0]) !== "string"
            || gettype($_start_datetime[1]) !== "string"
            || gettype($_start_datetime[2]) !== "string")
            throw new \LogicException('$_start_datetimeの引数が不正です。');

        if(gettype($_court_names) !== "array")
            throw new \LogicException('$_court_namesの引数が不正です。');
        
        for($i = 0; $i < count($_start_datetime); $i++)
        {
            if(gettype($_start_datetime[$i]) !== "string")
                throw new \LogicException('$_court_namesの引数が不正です。');
        }

        //値チェック
        if($_duration_hour <= 0){
            throw new \LogicException('$_duration_hourは1以上の値を指定してください。');
        };

        $this->account = $_account;
        $this->facility_name = $_facility_name;
        $this->court_names = $_court_names;
        $this->start_datetime = $_start_datetime;
        $this->duration_hour = $_duration_hour;

    }

    public function get_start_datetime()
    {
        
    }

}
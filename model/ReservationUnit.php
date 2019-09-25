<?php
class ReservationUnit
{
    public $facility_name;
    public $court_name;
    public $start_datetime;
    public $end_datetime;

    public function __construct(string $_facility_name,string $_court_name, $_start_datetime, $_end_datetime){

        $this->facility_name = $_facility_name;
        $this->court_name = $_court_name;
        $this->start_datetime = $_start_datetime;
        $this->end_datetime = $_end_datetime;
    }

}
<?php
class ReservationUnit
{
    public $facility_name;
    public $court_name;
    public $datetime;

    public function __construct(string $_facility_name,string $_court_name, $_datetime){

        $this->facility_name = $_facility_name;
        $this->court_name = $_court_name;
        $this->datetime = $_datetime;
    }

}
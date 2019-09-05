<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriver;

require_once './model/CourtReserverBase.php';
require_once './model/ReservationInformation.php';

/**
 * コート予約・抽選のベース
 */
Class CourtReserver extends CourtReserverBase
{
    public function __construct(string $selenium_server_url, string $start_url, string $frame_name, $reservation_information)
    {
        parent::__construct($selenium_server_url, $start_url, $frame_name, $reservation_information);
    }

    public function reserve()
    {
        $this->reserve_base(true);
    }



}

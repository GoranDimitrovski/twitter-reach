<?php

namespace App\Interfaces;

interface TwitterReachInterface
{

    public function getTweetReach($url);

    public function updateReachBulk();

}
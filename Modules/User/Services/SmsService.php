<?php

namespace Modules\User\Services;

class SmsService
{
    private $title, $body, $mobile;

    public function __construct($title, $body, $mobile)
    {
        $this->mobile = $mobile;
        $this->body = $body;
        $this->title = $title;
    }

    public function run(){
        //
    }
}

<?php
namespace app\controllers;

use app\models\Channel;
use app\models\Subscription;
use core\Controller;

class TelegramController extends Controller{

    public function index()
    {
        Subscription::firstOrCreate([
            'ip' => $_SERVER['REMOTE_ADDR']
        ],
            [
            'order' => 1,
            'utm_source' => $_GET['utm_source'],
            'utm_medium' => $_GET['utm_medium'],
            'utm_campaign' => $_GET['utm_campaign'],
            'status' => 'passed_over'
        ]);
        $channel = Channel::where('number',1)->first();
        header('Location: https://t.me/'.$channel->name);
    }
}

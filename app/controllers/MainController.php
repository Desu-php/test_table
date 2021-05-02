<?php

namespace app\controllers;


use app\models\Stat;
use app\models\Subscription;
use core\Controller;
use core\View;
use Illuminate\Database\Query\Builder;

class MainController extends Controller
{
    public function index()
    {
        if (empty($_GET['start_date']) && empty($_GET['end_date'])){
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        }else{
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
        }

        $datas = Subscription::whereDate('created_at','>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->orderBy('utm_source', 'DESC')
            ->where('status', 'subscribed')
            ->with('channel')
            ->get();
        $utm_sources = $datas->groupBy('utm_source');
        $count = $datas->where('order', 1)->count();
        $temp = [];
        foreach ($utm_sources as $source => $utm_source){
            $channel2_percent = 0;
            $channel2_count = 0;

            $channel3_percent = 0;
            $channel3_count = 0;

            $tempCount = $datas->where('utm_source', $source)->where('channel.number', 1)->count();
            $temp[$source]['count'] = $tempCount;
            $temp[$source]['percent'] = round($tempCount * 100 / $count);
            $mediums = $datas->where('utm_source', $source)->groupBy('utm_medium');
            foreach ($mediums as $medium => $utm_medium){
                $channel2_percent_med = 0;
                $channel2_count_med = 0;

                $channel3_percent_med = 0;
                $channel3_count_med = 0;

                $tempCount = $datas
                    ->where('order', 1)
                    ->where('utm_source', $source)
                    ->where('utm_medium', $medium)
                    ->count();
                $temp[$source]['mediums'][$medium]['count'] = $tempCount;
                $temp[$source]['mediums'][$medium]['percent'] = round( $tempCount * 100/ $count);
                $campaigns = $datas->where('utm_source', $source)
                    ->where('utm_medium', $medium)->groupBy('utm_campaign');

                foreach ($campaigns as $campaign => $utm_campaing){

                    $channel2_percent_cam = 0;
                    $channel2_count_cam= 0;

                    $channel3_percent_cam = 0;
                    $channel3_count_cam = 0;

                    $tempCount = $datas
                        ->where('order', 1)
                        ->where('utm_source', $source)
                        ->where('utm_medium', $medium)
                        ->where('utm_campaign', $campaign)
                        ->count();
                    $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['count'] = $tempCount;
                    $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['percent'] = round( $tempCount * 100/ $count);
                    $user_id = $datas
                        ->where('order', 1)
                        ->where('utm_source', $source)
                        ->where('utm_medium', $medium)
                        ->where('utm_campaign', $campaign);

                    $users_id = [];
                    foreach ($user_id as $value){
                        $users_id [] = $value->user_id;
                    }

                    $posts1 = $datas
                        ->where('order', 2)
                        ->where('channel.number', 2)
                        ->whereIn('user_id', $users_id)
                        ->groupBy('utm_content');

                    if ($posts1->count() == 0){

                        $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['posts']['Нет подписки']['count'] = 0;
                        $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['posts']['Нет подписки']['percent'] = 0;

                        $posts2 = $datas
                            ->where('channel.number', 3)
                            ->whereIn('order', [2,3])
                            ->whereIn('user_id', $users_id)
                            ->groupBy('utm_term');

                        foreach ($posts2 as $post2 => $items2){
                            $tempCount = count($items2);
                            $percent3 = round($tempCount * 100 / $count);

                            $channel3_count += $tempCount;
                            $channel3_percent += $percent3;

                            $channel3_count_med += $tempCount;
                            $channel3_percent_med += $percent3;

                            $channel3_count_cam += $tempCount;
                            $channel3_percent_cam += $percent3;

                            $channel3_count3  += $tempCount;
                            $channel3_percent3  += $percent3;

                            $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['posts']['Нет подписки']['posts'][$post2]['count'] = $tempCount;
                            $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['posts']['Нет подписки']['posts'][$post2]['percent'] = $percent3;
                        }
                    }else{
                        foreach ($posts1 as $post => $items){
                            $channel3_count3 = 0;
                            $channel3_percent3 = 0;

                            $tempCount = count($items);
                            $percent2 = round($tempCount * 100 / $count);

                            $channel2_count += $tempCount;
                            $channel2_percent += $percent2;

                            $channel2_count_med += $tempCount;
                            $channel2_percent_med += $percent2;

                            $channel2_count_cam += $tempCount;
                            $channel2_percent_cam += $percent2;

                            $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['posts'][$post]['count'] = $tempCount;
                            $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['posts'][$post]['percent'] = $percent2;

                            $posts_users_id = [];

                            foreach ($items as $item){
                                $posts_users_id[] = $item->user_id;
                            }

                            $posts2 = $datas
                                ->where('channel.number', 3)
                                ->whereIn('user_id', $users_id)
                                ->groupBy('utm_term');

                            if ($medium == 'jacobs'){
                                var_dump($posts2);
                                exit();
                            }
                            foreach ($posts2 as $post2 => $items2){
                                $tempCount = count($items2);
                                $percent3 = round($tempCount * 100 / $count);

                                $channel3_count += $tempCount;
                                $channel3_percent += $percent3;

                                $channel3_count_med += $tempCount;
                                $channel3_percent_med += $percent3;

                                $channel3_count_cam += $tempCount;
                                $channel3_percent_cam += $percent3;

                                $channel3_count3  += $tempCount;
                                $channel3_percent3  += $percent3;

                                $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['posts'][$post]['posts'][$post2]['count'] = $tempCount;
                                $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['posts'][$post]['posts'][$post2]['percent'] = $percent3;
                            }

                            $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['posts'][$post]['channel_3']['count'] = $channel3_count3;
                            $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['posts'][$post]['channel_3']['percent'] = $channel3_percent3;
                        }
                    }


                    $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['channel_2']['count'] = $channel2_count_cam;
                    $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['channel_2']['percent'] = $channel2_percent_cam;

                    $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['channel_3']['count'] = $channel3_count_cam;
                    $temp[$source]['mediums'][$medium]['campaigns'][$campaign]['channel_3']['percent'] = $channel3_percent_cam;

                }

                $temp[$source]['mediums'][$medium]['channel_2']['count'] = $channel2_count_med;
                $temp[$source]['mediums'][$medium]['channel_2']['percent'] = $channel2_percent_med;

                $temp[$source]['mediums'][$medium]['channel_3']['count'] = $channel3_count_med;
                $temp[$source]['mediums'][$medium]['channel_3']['percent'] = $channel3_percent_med;

            }
            $temp[$source]['channel_2']['count'] = $channel2_count;
            $temp[$source]['channel_2']['percent'] = $channel2_percent;

            $temp[$source]['channel_3']['count'] = $channel3_count;
            $temp[$source]['channel_3']['percent'] = $channel3_percent;
        }
        $data = $temp;
        View::display('index', compact('stats', 'data'));
    }

}

<?php
namespace app\controllers;


use app\models\Test;
use core\Controller;
use core\Response;
use core\View;

class MainController extends Controller
{
    public function index()
    {
        View::display('index');
    }

    public function getData()
    {
        $limit = 10;
        $orderBy = $_GET['orderby'];
        $orderType = $_GET['ordertype'];
        $column = $_GET['column'];
        $conditions = $_GET['conditions'];
        $filterValues = $_GET['value'];
        $test = new Test();
        $countData = new Test();

        $page = isset($_GET['page'])?$_GET['page']:1;
        $offset = ($page - 1) * $limit;

        $data = $test->select()->offset($offset)->limit($limit);


        if (!empty($orderBy) && !empty($orderType)){
            if ($orderType == 'ASC' || $orderType == 'DESC'){
                $data->orderBy($orderBy, $orderType);
            }
        }


        if (!empty($filterValues)){
            if (!empty($column) && !empty($conditions)){
                if ($result = $this->getConditions($conditions, $filterValues)){
                    $data->where($column,$result['operator'], $result['value']);
                    $countData->where($column,$result['operator'], $result['value']);

                }
            }
        }
        $count = $countData->count();
        $lastPage = ceil($count/$limit) ;

        return Response::json(['last_page' => $lastPage,'count_data' => $count, 'data' => $data->get()]);
    }

    private function getConditions($conditions,$values)
    {
        switch ($conditions) {
            case 'равно':
                return ['operator' => '=', 'value' => $values];
            case 'содержить':
                return ['operator' => 'LIKE', 'value' => '%'.$values.'%'];
            case 'больше':
                return ['operator' => '>', 'value' => $values];
            case 'меньше':
                return ['operator' => '<', 'value' => $values];
            default:
                return  false;
        }
    }

}

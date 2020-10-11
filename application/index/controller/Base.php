<?php
namespace app\index\controller;

use app\admin\model\Fd;
use think\Controller;
use Request;
use think\Db;

header('Content-Type: text/html; charset=UTF-8');
class Base extends Controller
{
    public function initialize( )
    {
        if(empty(session('userId'))){
            $this->redirect('Common/login');
        }

    }
}

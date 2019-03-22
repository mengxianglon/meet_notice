<?php
namespace Jcys\Controller;
use Think\Controller;
class BgController extends Controller {
    public function _initialize()
    {
        if (!session('user')){
          $this->redirect('/Jcys/Login/index');
        }elseif (session('admin')){
            $this->redirect('/Jcys/Index/index');
        }
    }

}
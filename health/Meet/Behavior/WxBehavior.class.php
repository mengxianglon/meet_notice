<?php
namespace Meet\Behavior;
use Vendor\Wxjssdk\Jssdk;
use Think\Controller;

class WxBehavior extends Controller{
    public function run()
    {
       // echo '行为触发了！';
        //echo '<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>';
        $jssdk = new Jssdk(C('APPID'), C('APPSCRET'));
        $signPackage = $jssdk->GetSignPackage();
        //print_r($signPackage);
        $this->assign('signPackage',$signPackage);
        $this->assign('getroot', get_root());

    }
}
<?php
namespace Jcys\Controller;
use Think\Controller;
class LoginController extends Controller {
        //登录界面
    public function index(){
        if (session('user')){
            $this->redirect(U('Jcys/Index/index'));
        }else{
            $this->display();
        }

    }
    //登录管理
    public function manageLogin(){
        if (IS_POST){
            $data =array(
                'user_login'=>trim(I('username')),
                'password'=>trim(I('password')),
            );
            $User = D("User")->login($data); // 实例化User对象
           if ($User>0){
               $this->success('恭喜你，登录成功',U('Jcys/Index/index'));
           }else{
               $this->error($User);
           }
            echo $User;
        }else{
            echo '非法登录,不是通过post进来的';
        }
    }
    //注册页面
    public function register(){
		echo '注册不对外部开放';
        //$this->display();
    }
    //注册管理
    public function manageReg(){
        if (IS_POST){
            $data =array(
                'user'=>trim(I('reg_username')),
                'password'=>trim(I('reg_password')),
                'repassword'=>trim(I('repassword')),
                'email'=>trim(I('email')),
                'login_ip'=>get_client_ip()
            );
            $User = D('User')->manageReg($data);
            if ($User>0){
                $this->success('恭喜你，注册成功',U('Jcys/Login/index'));
            }else{
                $this->error($User);
            }
        }else{
            echo '非法注册,不是通过post进来的';
        }
    }

    public function loginout(){
        //注销session
        session('user',null);
        $this->success('登出成功',U('Jcys/Login/index'));
    }

}
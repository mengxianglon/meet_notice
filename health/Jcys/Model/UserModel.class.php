<?php
namespace Jcys\Model;
use Think\Model;

class UserModel extends Model
{
    protected $_validate = array(
        array('user', '4,15', '账号在4-15个字符之间', self::EXISTS_VALIDATE, 'length'),
        array('user_login', '4,15', '账号在4-15个字符之间', self::EXISTS_VALIDATE, 'length'),
        array('user','','帐号被占用', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),     //新增时验证'帐号被占用！'
        array('email', 'email', '邮箱格式不正确', self::EXISTS_VALIDATE),
        array('password', '6,15', '密码必须在6-15位之间', self::EXISTS_VALIDATE, 'length'),
        array('repassword', 'password', '密码确认不正确', self::EXISTS_VALIDATE, 'confirm'), // 验证确认密码是否和密码一致

    );
    protected $_auto = array(
        array('password', 'md5', self::MODEL_BOTH, 'function'),
        array('last_login', 'time', self::MODEL_BOTH, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),

        //$data['last_login']= NOW_TIME;
    );
    public function login($data)
    {
        if ($this->create($data)) {
                $where['user'] =$data['user_login'];
                $where['password']=md5($data['password']);
            $obj = $this->field('id,user')->where($where)->find();
            if ($obj) {
//                $where['user'] =$data['user_login'];
               $where['status']=0;
                if ($this->field('status')->where($where)->find()){
                    return '账号已被禁用';
                }
                $update = array(
                    'id' => $obj['id'],
                    'user'=>$data['user_login'],
                    'last_login'=>time(),
                );
                session('user',$obj);
                $this->save($update);
                return $obj['id'];
            } else {

                return '账号或密码错误';
            }
        } else {
            return $this->getError();
        }
    }

    public function manageReg($data)
    {

        if ($this->create($data)) {
               // $data['login_ip'] =get_client_ip();//这里在验证的时候，用回调函数，这个不行
            if($this->add()){
                return 1;
            }else{
                return '注册失败model';
            }
        }else{
            return $this->getError();
        }
    }
}
<?php
namespace Jcys\Controller;

class IndexController extends BgController
{
    public function index()
    {
        //返回前台
        $this->assign('home', 'http://' . $_SERVER['SERVER_NAME'] . '/Yabao/Index/Index');
        //var_dump(session('user'));
        //登录获得个人信息
		$session = session('user');
        $this->assign('user',$session['user']);
        //切换账号
        $this->display();
    }

    //截取中文字符串
    public function utf8Substr($str, $len)
    {
        $str = strip_tags($str);
        return mb_substr($str, 0, $len, 'utf-8');
    }

    public function welcome()
    {
        $this->display();
    }

    //会员列表
    public function lists()
    {
        //分页
        $User = M('User'); // 实例化User对象
        $count = $User->count('id');// 查询满足要求的总记录数
        $Page = new \Think\Page($count, 12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->order('id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        // print_r($list);
        $this->assign('userList', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
//        //共几条数据
        $this->assign('total', $count);
        $this->display();// 输出模板
    }

    //搜索会员
    public function searchUser()
    {
        $like = trim(I('searchUsername'));
        if (strlen($like) > 0) {
            $map['user'] = array('like', "%$like%");
            $user = M('User');
            //分页
            $count = $user->where($map)->count();// 查询满足要求的总记录数
            $Page = new \Think\Page($count, 12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $user->where($map)->order('id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
            $this->assign('userList', $list);// 赋值数据集
            $this->assign('page', $show);// 赋值分页输出
            if ($count < 1) {
                $this->assign('nouser', '没有找到数据');
            }
            //共几条数据
            $this->assign('total', $count);
            $this->assign('user', $list);//没有分页输出数据
            $this->display();// 输出模板
        } else {
            echo '传入了空字符串';
        }
    }

    public function arlist()
    {
        $this->display();
    }

    //会员密码
    public function upassword()
    {
        $where['id'] = trim(I('id'));
        $res = M('User')->field('user')->where($where)->find();
        $this->assign('user', $res);
        $this->display();
    }

    //会员密码修改
    public function unpass()
    {
        if (IS_AJAX) {
            $data = array(
                'user' => trim(I('username')),
                'password' => md5(trim(I('newpass')))
            );
            //  print_r($data);
            $map['user'] = $data['user'];
            $res = M('User')->where($map)->save($data);
            if ($res) {
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
        }
    }

    //编辑会员信息show
    public function uedit()
    {
        if (IS_GET) {//get得到id后查找对应的人员
            $where['id'] = trim(I('id'));
            $res = M('User')->where($where)->find();
            //print_r($res);
            $this->assign('user', $res);
            $this->display();
        } else {
            echo '没有找到相应的id值';
        }
    }

    //编辑会员信息edit
    public function pedit()
    {
        if (IS_POST) {
            $res = M('User');
            $data =array(
                'user' => trim(I('username')),
                'sex' => trim(I('sex')),
                'phone' => trim(I('phone')),
                'email' => trim(I('email')),
                'address' => trim(I('address')),
            );
            $map['user'] = trim(I('username'));
            if ($res->where($map)->save($data)) {
                $this->success('修改成功');
                //print_r($data);
            } else {
                $this->error('修改失败');
                //print_r($data);
            }
        } else {
            echo '不是通过提交进来的。';
        }
    }

    //
    public function adduser()
    {
        if (IS_AJAX) {
            $data = array(
                'user' => trim(I('username')),
                'password' => md5(trim(I('pass'))),
                'sex' => trim(I('sex')),
                'phone' => trim(I('phone')),
                'email' => trim(I('email')),
                'address' => trim(I('address')),
                'last_login' => time(),
                'create_time' => time(),
                'login_ip' => get_client_ip(),
            );
            //print_r($data);
            $res = M('User')->add($data);
            if ($res) {
                $this->success('添加成功成功');
            } else {
                $this->error('添加成员失败');
            }
        }
        $this->display();
    }

    //状态，启用禁用
    public function statuss()
    {
        if (IS_AJAX) {
            $data = array(
                'id' => I('id'),
            );
            $res = M('User');
            $sql = $res->where($data)->find();
            if ($sql) {
                $map['status'] = I('status');
                $res->where($data)->save($map);
                $this->success('修改状态成功');
            } else {
                $this->error('没有找到相应的id');
            }
        } else {
            echo '非法temp';
        }
    }

    public function del()
    {
        if (IS_AJAX) {
            $data['id'] = I('id');
            $sql = M('User')->where($data)->find();
            if ($sql) {
                $res = M('User')->where($data)->delete();
                $res ? $this->success('删除成功controller') : $this->error('删除失败controller');
            } else {
                $this->error('没有找到用户controller');
            }

        } else {
            $this->success('非法删除，删除失败controller');
        }
    }

    public function upload()
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 214572800;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = './Uploads/wang_pic/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        $upload->replace = true;//覆盖同名文件
        // 上传文件
        $info = $upload->upload();
        //print_r($info);

        //$name = array_column($info, 'savepath','savename');

        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        } else {// 上传成功
            //$this->success('上传成功！');
            foreach ($info as $file) {
                $path['url'] = 'http://' . $_SERVER['SERVER_NAME'] . '/Uploads/wang_pic/' . $file['savepath'] . $file['savename'];
            }
            $this->ajaxReturn($path);
        }
    }

    public function addarticle()
    {
        //$array=$this->typeSize();
        if (IS_POST) {
            $upload = new \Think\Upload();
            $upload->maxSize = 9999999;// 设置附件上传大小
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath = './Public/'; // 设置附件上传根目录
            $upload->savePath = './Yabao/cover/'; // 设置附件上传（子）目录
            $upload->autoSub = false;
            if (!file_exists($upload->rootPath)) {
                mkdir($upload->rootPath);
            }
            if (!file_exists("./Public/Yabao/cover")) {
                mkdir("./Public/Yabao/cover");
            }
            $info = $upload->upload();
               if ($info) {
                $rename_pic = $info["image0"]["savename"];//重新命名的图片
                $_POST["image"] = '/Public/Yabao/cover/' . $rename_pic;
            }
			$_POST["addtime"] = time();
            $id = M("news")->add($_POST);
            if ($id) {
                echo $this->success("添加成功");
            } else {
                echo $this->error("添加失败");
            }

        }
        $this->display();
    }

    //展示修改页面和数据
    public function newedit()
    {
        $data = array('nid' => I('nid'));
        $result = M('news')->where($data)->find();
        $this->assign('edit', $result);
        $this->display();
    }

    //修改页面数据修改操作
    public function edit_data()
    {
        $data = array('nid' => I('nid'));
        $result = M('news')->where($data)->find();
        if ($result) {
            $upload = new \Think\Upload();
            $upload->maxSize = 9999999;// 设置附件上传大小
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath = './Public/'; // 设置附件上传根目录
            $upload->savePath = './Yabao/cover/'; // 设置附件上传（子）目录
            $upload->autoSub = false;
            if (!file_exists($upload->rootPath)) {
                mkdir($upload->rootPath);
            }
            if (!file_exists("./Public/Yabao/cover")) {
                mkdir("./Public/Yabao/cover");
            }
            $info = $upload->upload();
             if ($info) {
                $rename_pic = $info["image0"]["savename"];//重新命名的图片
                $_POST["image"] = '/Public/Yabao/cover/' . $rename_pic;
            }
            M('news')->where($data)->setField($_POST) ? $this->success('修改成功') : $this->error('修改失败，可能没有修改任何内容');
        } else {
            $this->error('要修改的文章不存在,请刷新页面后尝试');
        }
    }

    public function newshow()
    {
        $data = array('type' => I('type'));
        if (IS_GET) {
            $total = M('news')->field('nid')->where($data)->count();
            $count = $total;// 查询满足要求的总记录数
            $Page = new \Think\Page($count, 7);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = M('news')->order('addtime desc')->field('nid,title,image,addtime,content,show')->limit($Page->firstRow . ',' . $Page->listRows)->where($data)->select();
            foreach ($list as $k => $v) {
                $list[$k]["content"] = $this->utf8Substr($v['content'], 20);
            }
            $this->assign('list', $list);// 赋值数据集
            $this->assign('page', $show);// 赋值分页输出
            $this->display();// 输出模板
            //$json =$this->ajaxReturn($list);
            //$list ? $this->success($list) : $this->error('下拉菜单失败newshow,该栏目没有数据');
        } else {
            $total = M('news')->field('nid')->count();
            $count = $total;// 查询满足要求的总记录数
            $Page = new \Think\Page($count, 7);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = M('news')->order('addtime desc')->field('nid,title,image,addtime,content,show')->limit($Page->firstRow . ',' . $Page->listRows)->select();
            foreach ($list as $k => $v) {
                $list[$k]["content"] = $this->utf8Substr($v['content'], 20);
            }
            $this->assign('list', $list);// 赋值数据集
            $this->assign('page', $show);// 赋值分页输出
            $this->display();// 输出模板
        }
    }
    public function newstatus(){
        $result =M('news');
        $where=array('nid'=>I('nid'));
        if (IS_POST){
          $find=$result->where($where)->find();
          if ($find['show'] == 1){
                $result->where($where)->setField('show',0);
                $this->success('0');
          }else{
              $result->where($where)->setField('show',1);
              $this->success('1');
          }

        }
    }
    public function newsdel()
    {
        if (IS_POST) {
            $date = I('nid');
            $result = M('news')->find($date);
            if (!$result) {
                echo $this->error('没有找到id');
                return false;
            }
            $del = M('news')->delete($date);
            if ($del) {
                echo $this->success('删除成功');
            } else {
                echo $this->error('删除失败');
            }
        }
    }
    //截屏大小
    public function typeSize(){
        $type=I("type");
        $type=in_array($type,array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16))?$type:1;
        switch($type){
            case 1:
                return array("415*290");
                break;
            case 2:
                return array("265*170","190*122");
                break;
            case 3:
                //从上至下，从左至右图片截取大小，其他地方相同
                return array("352*226","365*420","178*114","365*234","336*216","150*90","190*122","405*260");
                break;
            case 4:
                return array("260*167");
                break;
            case 5:
                return array("202*140");
                break;
            case 6:
                return array("202*140");
                break;
            case 7:
                return array("260*167");
                break;
            case 8:
                return array("260*167");
                break;
            case 9:
                return array("260*167");
                break;
            case 10:
                return array("704*452");
                break;
            case 11:
                return array("114*112");
                break;
            case 12:
                return array("453*242");
                break;
            case 13:
                return array("265*170","190*122");
                break;
            case 14:
                return array("420*270");
                break;
            case 15:
                return array("260*167");
                break;
            case 16:
                return array("260*167");
                break;
            default:
                return "";
                break;
        }
    }
}
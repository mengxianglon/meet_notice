<?php
namespace Jcys\Controller;

class MeetController extends BgController
{

    //截取中文字符串
    public function utf8Substr($str, $len)
    {
        $str = strip_tags($str);
        return mb_substr($str, 0, $len, 'utf-8');
    }
    public function addmeet()
    {
        //$array=$this->typeSize();
        if (IS_POST) {
            $upload = new \Think\Upload();
            $upload->maxSize = 9999999;// 设置附件上传大小
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath = './Public/'; // 设置附件上传根目录
            $upload->savePath = './Meet/cover/'; // 设置附件上传（子）目录
            $upload->autoSub = false;
            if (!file_exists($upload->rootPath)) {
                mkdir($upload->rootPath);
            }
            if (!file_exists("./Public/Meet/cover")) {
                mkdir("./Public/Meet/cover");
            }
            $info = $upload->upload();
               if ($info) {
                $rename_pic = $info["image0"]["savename"];//重新命名的图片
                $_POST["image"] = '/Public/Meet/cover/' . $rename_pic;
            }
			$_POST["addtime"] = time();
            $id = M()->table('t5ve_meet')->add($_POST);
            if ($id) {
                echo $this->success("添加成功");
            } else {
                echo $this->error("添加失败");
            }

        }
        $this->display();
    }

    //展示修改页面和数据
    public function meetedit()
    {
        $data = array('mid' => I('mid'));
        $result =  M()->table('t5ve_meet')->where($data)->find();
        $this->assign('edit', $result);
        $this->display();
    }

    //修改页面数据修改操作
    public function edit_data()
    {
        $data = array('mid' => I('mid'));
        $result =  M()->table('t5ve_meet')->where($data)->find();
        if ($result) {
            $upload = new \Think\Upload();
            $upload->maxSize = 9999999;// 设置附件上传大小
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath = './Public/'; // 设置附件上传根目录
            $upload->savePath = './Meet/cover/'; // 设置附件上传（子）目录
            $upload->autoSub = false;
            if (!file_exists($upload->rootPath)) {
                mkdir($upload->rootPath);
            }
            if (!file_exists("./Public/Meet/cover")) {
                mkdir("./Public/Meet/cover");
            }
            $info = $upload->upload();
             if ($info) {
                $rename_pic = $info["image0"]["savename"];//重新命名的图片
                $_POST["image"] = '/Public/Meet/cover/' . $rename_pic;
            }
            M()->table('t5ve_meet')->where($data)->setField($_POST) ? $this->success('修改成功') : $this->error('修改失败，可能没有修改任何内容');
        } else {
            $this->error('要修改的文章不存在,请刷新页面后尝试');
        }
    }

    public function meetshow()
    {
        $data = array('type' => I('type'));
        if (IS_GET) {
            $total = M()->table('t5ve_meet')->field('mid')->where($data)->count();
            $count = $total;// 查询满足要求的总记录数
            $Page = new \Think\Page($count, 7);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = M()->table('t5ve_meet')->order('addtime desc')->field('mid,title,image,addtime,content,is_show')->limit($Page->firstRow . ',' . $Page->listRows)->where($data)->select();
            foreach ($list as $k => $v) {
                $list[$k]["content"] = $this->utf8Substr($v['content'], 20);
            }
            $this->assign('list', $list);// 赋值数据集
            $this->assign('page', $show);// 赋值分页输出
            $this->display();// 输出模板
            //$json =$this->ajaxReturn($list);
            //$list ? $this->success($list) : $this->error('下拉菜单失败newshow,该栏目没有数据');
        } else {
            $total = M()->table('t5ve_meet')->field('mid')->count();
            $count = $total;// 查询满足要求的总记录数
            $Page = new \Think\Page($count, 7);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = M()->table('t5ve_meet')->order('addtime desc')->field('mid,title,image,addtime,content,is_show')->limit($Page->firstRow . ',' . $Page->listRows)->select();
            foreach ($list as $k => $v) {
                $list[$k]["content"] = $this->utf8Substr($v['content'], 20);
            }
            $this->assign('list', $list);// 赋值数据集
            $this->assign('page', $show);// 赋值分页输出
            $this->display();// 输出模板
        }
    }
    public function meetstatus(){
        $where=array('mid'=>I('mid'));
        if (IS_POST){
          $find=M()->table('t5ve_meet')->where($where)->find();
          if ($find['is_show'] == 1){
              M()->table('t5ve_meet')->where($where)->setField('is_show',0);
                $this->success('0');
          }else{
              M()->table('t5ve_meet')->where($where)->setField('is_show',1);
              $this->success('1');
          }
        }
    }
    public function meetdel()
    {
        if (IS_POST) {
            $date =I('mid');
            //$date=array('mid'=>I('mid'));
            $result = M()->table('t5ve_meet')->find($date);
            if (!$result) {
                echo $this->error('没有找到mid');
                return false;
            }
            $sql ="DELETE FROM t5ve_meet WHERE mid = $date ";
            $del = M('','t5ve_meet')->execute($sql);
            if ($del) {
                echo $this->success('删除成功');
            } else {
                echo $this->error('删除失败');
            }
        }
    }
    public function meetcon()
    {
        $this->display();
    }
}
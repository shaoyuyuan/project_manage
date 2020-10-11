<?php
namespace app\index\controller;
use think\facade\Request;
use Db;

class User extends Base
{

    /**
     * 用户管理列表
     * @author 邵禹源 20200806 新增
     */
    public function index()
    {

        $list  = db('user')
            ->alias('user')
            ->field('user.*, role.name as role')
            ->join('role', 'role.id=user.role_id')
            ->where('user.id <> 1')
            ->order('id desc')
            ->paginate(10, false,['query' => Request::param()]);

        //角色
        $roles = db('role')
                ->where([['id', '<>', 1]])
                ->select();

        $this->assign('list', $list);
        $this->assign('roles', $roles);

        return $this->fetch();
    }

    /**
     * 保存用户
     * @author 邵禹源 20200806  新增
     */
    public function save() {
        $username = input('username');
        $id = input('id');
        if (!$id) {
            //新增
            if (db('user')->where('username', $username)->find()) {
                return json(['status'=>1, 'msg'=>'用户名重复']);
            }
            //权限名
            $role = db('role')->where('id', input('role_id'))->value('name');
            db('user')
                ->insertGetId([
                    'username' => $username,
                    'pwd' => md5(input('pwd')),
                    'name' => input('name'),
                    'role_id' => input('role_id'),
                    'operation' => $role,
                    'c_dt'    => date('Y-m-d H:i:s')
                ]);
        } else {
            //修改
            db('user')
                ->where('id', $id)
                ->update([
                   'name'  => input('name'),
                   'pwd' => md5(input('pwd')),
                ]);
        }

        return json(['status'=>0, 'msg'=>'成功']);
    }

}

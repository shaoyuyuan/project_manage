<?php
    
    namespace app\index\controller;
    
    use app\admin\model\User;
    use app\admin\model\Fd;
    use think\Controller;
    use think\facade\Session;
    use Request, Db;
    
    class Common extends Controller
    {
        public function login( )
        {
            return $this->fetch('common/login');
        }
        
        
        public function loginSys()
        {
            $username = Request::param('username');
            $password = Request::param('password');
            $info     = db('user')
                    ->alias('user')
                    ->field('user.*,role.name as role')
                    ->join('role', 'role.id=user.role_id')
                    ->where('user.username', $username)
                    ->find();
            if (empty($info)) {
                // return json(['status' => 1, 'msg' => '当前账号不存在']);
                $this->error('当前账号不存在');
            }
            if (MD5($password) == $info['pwd']) {
                Session::set('userId', $info['id']);
                Session::set('username', $info['username']);
                Session::set('name', $info['name']);
                Session::set('useroperation', $info['role']);

                if ($info['role'] == '查看人') {
                    $this->redirect('Query/index');
                } else {
                    $this->redirect('Index/index');
                }

            } else {
                // return json(['status' => 1, 'msg' => '密码错误']);
                $this->error('密码错误');
            }
        }
        
        /**
         * 退出登录
         */
        public function outLogin()
        {
            Session::clear();
            $this->redirect('Common/login');
        }
        



    }

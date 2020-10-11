<?php
namespace app\index\controller;
use think\facade\Request;
use Db;

class Query extends Base
{
    //项目等级
    protected $level = [
            ['name' => '一般','css' => 'info'],
            ['name' => '重要','css' => 'warning'],
            ['name' => '加急','css' => 'danger'],
        ];
    //部门
    protected  $department = ['研发部', '企划部', '销售部'];
    //分页条数
    protected $page = 30;

    /**
     * 项目列表
     * @author 邵禹源 20200806 新增
     */
    public function index()
    {
        $title = input('title');
        $department  = input('department');
        $level = input('level');

        $where[] = ['project.is_del', '=', 0];
        $sql = '';
        if ($title) {
            $sql = "project.title like '%{$title}%' or project.remark like '%{$title}%'";
        }
        if ($department) {
            $where[] = ['project.department', '=', $department];
        }
        if (isset($level) && $level != '-1') {
            $where[] = ['project.level', '=', $level];
        }

        $list = db('project')
            ->alias('project')
            ->field('project.*,user.name as c_name')
            ->join('user', 'user.id=project.user_id')
            ->where($where)
            ->where($sql)
//            ->orderRaw('project.level desc, (project.progress+1) asc')
            ->orderRaw('(project.progress+1) asc')
            ->paginate($this->page, false,['query' => Request::param()])
            ->each(function ($item, $key){
                $item['status'] = $this->level[$item['level']]['name'];
                $item['css'] = $this->level[$item['level']]['css'];
                return $item;
            });

        $this->assign('list',$list);
        $this->assign('query', Request::param());
        $this->assign('level',$this->level);
        $this->assign('department', $this->department);

        return $this->fetch();
    }

}

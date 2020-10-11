<?php
namespace app\index\controller;
use think\facade\Request;
use Db;

class Index extends Base
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
     * @author 邵禹源 20200803 新增
     */
    public function index()
    {
        $title = input('title');
        $department = input('department');
        $level = input('level');
        $person = input('person');

        $where[] = ['project.is_del', '=', 0];
        $sql = '';
        if ($title)  {
            $sql = "project.title like '%{$title}%' or project.remark like '%{$title}%'";
        }
        if ($department) {
            $where[] = ['project.department', '=', $department];
        }
        if ($person) {
            $where[] = ['project.person', 'like', "%" . $person . "%"];
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

        $this->assign('list', $list);
        $this->assign('query', Request::param());
        $this->assign('level', $this->level);
        $this->assign('department', $this->department);

        return $this->fetch();
    }

    /**
     * 保存项目
     * @author 邵禹源 20200804 新增
     * @return \think\response\Json
     */
    public function save()
    {
        $title = input('title');
        $id = input('id');

        $data = [
            'title' => $title,
            'remark' => input('remark'),
            'progress' => input('progress') ? input('progress') : 0,
            'level' => input('level', 0),
            'person' => input('person'),
        ];

        if (empty($id)) {
            //新建
            $id = db('project')->insertGetId([
                'c_dt'  => date('Y-m-d H:i:s'),
                'title' => $title,
                'remark' => input('remark'),
                'level' => input('level', 0),
                'person' => input('person'),
                'user_id' => session('userId'),
                'city' => input('city'),
                'question_person' => input('question_person'),
                'question_tel' => input('question_tel'),
                'department' => input('department'),
                'progress' => 0,
            ]);
            if (!$id) {
                return json(['status'=>1, 'msg'=>'新建失败']);
            }
        } else {
            //编辑
            $data = [
                'person' => input('person'),
                'progress' => input('progress'),
            ];
            if (input('start_time')) {
                $data['start_time'] = input('start_time');
            }
            if (input('end_time')) {
                $data['end_time'] = input('end_time');
            }
            //进度修改为100时，修改结束时间
            if ($data['progress'] == 100) {
                $data['end_time'] = date('Y-m-d H:i:s');
            }

            db('project')
                ->where('id', $id)
                ->update($data);
        }

        return json(['status'=>0, 'msg'=>'成功']);
    }

    /**
     * 软删除项目
     * @author 邵禹源 20200803 新增
     * @return \think\response\Json
     * @param int $id 项目id
     */
    public function del()
    {
        $id = input('id');
        if (empty($id)) {
            return json(['status'=>1, 'msg'=>'参数错误']);
        } else {
            db('project')->where('id', $id)->update(['is_del'=>1]);
        }
        return json(['status'=>0, 'msg'=>'成功']);
    }

    /**
     * 保存项目记录
     * @author 邵禹源 20200804 新增
     */
    public function saveRecord() {
        $pro_id = input('pro_id');

        if (empty($pro_id)) {
            return json(['status'=>1, 'msg'=>'参数错误']);
        }
        //负责人不填，默认项目负责人
        $project = db('project')
                ->where('id', $pro_id)
                ->value('person');
        $person = !empty(input('rec_person')) ? input('rec_person') : $project;


        if (db('record')
            ->insert([
                'project_id' => $pro_id,
                'c_time' => date('Y-m-d H:i:s'),
                'remark' => input('rec_remark'),
                'person' => $person
            ])
        ) {
            $data['progress'] = input('rec_progress');
            //进度修改为100时，修改结束时间
            if ($data['progress'] == 100) {
                $data['end_time'] = date('Y-m-d H:i:s');
            }
            //如填写进度则修改项目进度
            db('project')
                ->where('id', $pro_id)
                ->update($data);

            return json(['status'=>0, 'msg'=>'成功']);
        } else {
            return json(['status'=>1, 'msg'=>'添加项目记录失败']);
        }


    }

    /**
     * 查询项目详细信息
     * @author 邵禹源 20200805 新增
     */
    function getProject(){
        $id = input('id');
        if (!$id) {
            return json(['status'=>1, 'msg'=>'参数错误']);
        }
        $info = db('project')
            ->where('id', $id)
            ->find();
        if ($info['start_time']) {
            $info['start_time'] = substr($info['start_time'],0, 16);
        }
        if ($info['end_time']) {
            $info['end_time'] = substr($info['end_time'],0, 16);
        }
        if ($info) {
            return json(['status'=>0, 'msg'=>$info]);
        }

    }

    /**
     * 查询项目记录列表
     * @author 邵禹源 20200804 新增
     * @return \think\response\Json
     */
    public function queryProject() {

        $list = db('record')
            ->where('project_id', input('pro_id'))
            ->order('id asc')
            ->select();

        return json(['status'=>0, 'msg'=>$list]);

    }
}

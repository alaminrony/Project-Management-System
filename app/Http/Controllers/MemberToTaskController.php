<?php

namespace App\Http\Controllers;

use Validator;
use App\ProjectTeam;
use App\Project;
use App\User;
use App\MemberToTask;
use App\TaskToProject;
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use App\Task;
use Illuminate\Http\Request;

class MemberToTaskController extends Controller {

    private $controller = 'MemberToTask';

    public function create(Request $request) {
        $qpArr = $request->all();
        $projects = ['' => __('label.SELECT_PROJECT')] + Project::pluck('name', 'id')->toArray();
        $memberList = ['' => __('label.SELECT_MEMBERS')];
        return view('memberToTask.create')->with(compact('projects', 'memberList'));
    }

    public function getTasks(Request $request) {
        $memberList = ['' => __('label.SELECT_MEMBERS')] + ProjectTeam::join('users', 'project_team.member_id', '=', 'users.id')
                        ->where('project_team.project_id', $request->project_id)
                        ->select(DB::raw("CONCAT(users.first_name,' ',users.last_name) AS nick_name"), 'users.id')
                        ->pluck('nick_name', 'users.id')->toArray();

        $viewMember = view('memberToTask.members')->with(compact('memberList'))->render();
//      $viewTasks  = view('memberToTask.tasks')->with(compact('taskList','memberTaskList'))->render();
//        return response()->json(['viewMember' => $viewMember, 'viewTasks' => $viewTasks]);
        return response()->json(['viewMember' => $viewMember]);
    }

    public function getSingleUserTasks(Request $request) {
        $taskList = TaskToProject::join('task', 'task.id', '=', 'task_to_project.task_id')
                        ->where('task_to_project.project_id', $request->project_id)->select('task.title', 'task.id')->get();
        $memberTaskList = MemberToTask::where('member_id', $request->member_id)->pluck('task_id')->toArray();
        //Helper::dump($memberTaskList);
        $viewTasks = view('memberToTask.tasks')->with(compact('taskList', 'memberTaskList'))->render();
        return response()->json(['viewTasks' => $viewTasks]);
//       Helper::dump($memberTaskList);
    }

    public function store(Request $request) {
//        Helper::dump($request->all());
        $rules = [
            'project_id' => 'required|not_in:0',
            'member_id' => 'required',
            'task_id' => 'required',
        ];

        $message = [
            'project_id.required' => 'Project field must not be empty',
            'member_id.required' => 'Member field must not be empty',
            'task_id.required' => 'Task field must not be empty',
        ];


        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $deletePreviousTask = MemberToTask::where('member_id', $request->member_id)->delete();

        $data = [];
        $i = 0;
        foreach ($request->task_id as $task) {
            $data[$i]['project_id'] = $request->project_id;
            $data[$i]['member_id'] = $request->member_id;
            $data[$i]['task_id'] = $task;
            $data[$i]['updated_at'] = date('Y-m-d h:m:s');
            $data[$i]['updated_by'] = Auth::user()->id;
            $i++;
        }
        if (MemberToTask::insert($data)) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

}

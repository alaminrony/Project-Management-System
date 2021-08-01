<?php

namespace App\Http\Controllers;

use Validator;
use App\ProjectTeam;
use App\Project;
use App\User;
use App\TaskToProject;
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use App\Task;
use Illuminate\Http\Request;

class TaskToProjectController extends Controller {

    private $controller = 'TaskToProject';

    public function create(Request $request) {
        $qpArr = $request->all();
        $projects = ['' => __('label.SELECT_PROJECT')] + Project::pluck('name', 'id')->toArray();
        return view('taskToProject.create')->with(compact('projects'));
    }

    public function getTasks(Request $request) {
        $checkProjectTask = TaskToProject::where('project_id', $request->project_id)->pluck('task_id')->toArray();
        $taskToProjectArr = TaskToProject::where('project_id', $request->project_id)->get();
//        Helper::dump($taskToProjectArr->toArray());
        foreach ($taskToProjectArr as $taskToProject) {
            $taskToProjectList[$taskToProject->task_id]['task_id'] = $taskToProject->task_id;
            $taskToProjectList[$taskToProject->task_id]['start_time'] = $taskToProject->start_time;
            $taskToProjectList[$taskToProject->task_id]['end_time'] = $taskToProject->end_time;
            $taskToProjectList[$taskToProject->task_id]['prerequisite'] = $taskToProject->prerequisite;
        }
//        Helper::dump($taskToProjectList);
        $taskArray = Task::orderBy('order')->get();
        $taskData = view('taskToProject.tasks')->with(compact('taskArray', 'checkProjectTask', 'tasksDateAndPrerequisite', 'taskToProjectList'))->render();
        return response()->json(['taskData' => $taskData]);
    }

    public function store(Request $request) {
//        Helper::dump($request->all());
        $rules = [
            'project_id' => 'required|not_in:0',
            'task_id' => 'required',
        ];

        $message = [
            'project_id.required' => 'Project field must not be empty',
            'task_id.required' => 'Task field must not be empty',
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $deletePreviousTask = TaskToProject::where('project_id', $request->project_id)->delete();

        $data = [];
        $i = 0;
        foreach ($request->task_id as $task) {
            $data[$i]['project_id'] = $request->project_id;
            $data[$i]['task_id'] = $task;
            $data[$i]['start_time'] = $request->start_time[$task];
            $data[$i]['end_time'] = $request->end_time[$task];
            $data[$i]['prerequisite'] = $request->prerequisite[$task];
            $data[$i]['updated_at'] = date('Y-m-d h:m:s');
            $data[$i]['updated_by'] = Auth::user()->id;
            $i++;
        }
//        echo '<pre>';print_r($data);exit;
        if (TaskToProject::insert($data)) {
            return response()->json('success');
        } else {
            return response()->json('errors');
        }
    }

}

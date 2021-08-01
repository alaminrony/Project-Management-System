<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Debugbar;
use Helper;
use App\Designation;
use App\Bug;
use App\Task;
use App\TaskToProject;
use App\Project;
use App\Contact;
use App\Company;
use DB;
use App\BugFollowUp;

class DashboardController extends Controller {

    public function __construct() {
        //$this->middleware('auth');
    }

    public function index() {
        $openBugStatus = Bug::where('status', 1)->get();
//        Helper::dump($openBugStatus->toArray());
        $numberOfOpenBug = $openBugStatus->count();

        $inProgressBugStatus = BugFollowUp::select('bug_id')->where('status', '1')->distinct()->get();
        $numberOfProgressBug = $inProgressBugStatus->count();

        $haultBugStatus = BugFollowUp::select('bug_id')->where('status', '2')->distinct()->get();
        $numberOfHaultBug = $haultBugStatus->count();

        $closedBugStatus = BugFollowUp::select('bug_id')->where('status', '3')->distinct()->get();
        $numberOfClosedBug = $closedBugStatus->count();


        $taskIdArr = TaskToProject::select('task_id')->distinct()->pluck('task_id')->toArray();
        $taskTitle = Task::whereIn('id', $taskIdArr)->select('id', 'title')->get();

        $taskArr = [];
        if ($taskTitle->isNotEmpty()) {
            foreach ($taskTitle as $title) {
                $taskArr[$title->id] = $title->title;
            }
        }

        $taskToProject = TaskToProject::select('task_id', 'project_id')->get();

        $taskProjectArr = [];
        if ($taskToProject->isNotEmpty()) {
            foreach ($taskToProject as $taskProject) {
                $taskProjectArr[$taskProject->task_id][$taskProject->project_id] = $taskProject->project_id;
                $arrayTaskCount[$taskProject->task_id] = !empty($taskProjectArr[$taskProject->task_id]) ? count($taskProjectArr[$taskProject->task_id]) : '0';
            }
        }

        $totalProject = Project::count();
        $totalContact = Contact::count();
        $totalUsers = User::count();
        $totalBug = Bug::count();
        
        $recentUsers = User::join('designation','designation.id','=','users.designation_id')
                ->select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as user_name"),'designation.name as designation_name','users.created_at','users.photo')->latest()->take(3)->get();
        
        $recentProject = Project::join('company', 'company.id', '=', 'project.client_id')
                ->select('project.name','project.upload_file','project.created_at','company.name as company_name')->latest()->take(3)->get();
//        Helper::dump($recentProject->toArray());
        return view('admin.dashboard')->with(compact('openBugStatus', 'numberOfOpenBug', 'numberOfProgressBug', 'numberOfHaultBug', 'numberOfClosedBug', 'taskArr', 'arrayTaskCount', 'totalProject', 'totalBug', 'totalUsers', 'totalContact','recentUsers','recentProject'));
    }

    public function getBugDetails(Request $request) {
        $indexNumber = $request->index_number;
        $targetArr = Bug::join('project', 'project.id', '=', 'bug.project_id')
                ->join('reporting_medium', 'reporting_medium.id', '=', 'bug.reporting_medium')
                ->join('contact', 'contact.id', '=', 'bug.reported_by')
                ->join('bug_category', 'bug_category.id', '=', 'bug.severity_level')
                ->select('bug.id', 'bug.bug_title', 'project.name as project_name', 'bug.files', 'bug.reporting_date', 'reporting_medium.medium_name', DB::raw("CONCAT(contact.first_name,' ',contact.last_name) as reported_by"), 'bug_category.category_level', 'bug.status', 'bug.unique_code');

        if ($request->index_number == '0') {
            $targetArr = $targetArr->where('bug.status', '1')->get();
        }

        if ($request->index_number == '1') {
            $inProgressBugStatusArr = BugFollowUp::select('bug_id')->where('status', '1')->distinct()->pluck('bug_id', 'bug_id')->toArray();
            $targetArr = $targetArr->whereIn('bug.id', $inProgressBugStatusArr)->get();
        }
        if ($request->index_number == '2') {
            $haultBugStatus = BugFollowUp::select('bug_id')->where('status', '2')->distinct()->pluck('bug_id', 'bug_id')->toArray();
            $targetArr = $targetArr->whereIn('bug.id', $haultBugStatus)->get();
        }
        if ($request->index_number == '3') {
            $closedBugStatus = BugFollowUp::select('bug_id')->where('status', '3')->distinct()->pluck('bug_id', 'bug_id')->toArray();
            $targetArr = $targetArr->whereIn('bug.id', $closedBugStatus)->get();
        }

        $viewData = view('admin.viewBugModal')->with(compact('targetArr', 'indexNumber'))->render();
        return response()->json(['viewData' => $viewData]);
    }

    public function getTaskWiseProject(Request $request) {
        $taskProjectId = TaskToProject::where('task_id', $request->task_id)->pluck('project_id', 'project_id')->toArray();

        $targetArr = Project::join('company', 'company.id', '=', 'project.client_id')
                        ->join('project_status', 'project.project_status', '=', 'project_status.id')
                        ->select('project.id', 'project.name', 'company.name as company_name', 'project.tentative_date', 'project.dead_line', 'project.order', 'project.upload_file', 'project.status', 'project_status.name as status_name')
                        ->whereIn('project.id', $taskProjectId)->orderBy('project.id', 'asc')->get();
        $viewData = view('admin.viewProjectModal')->with(compact('targetArr'))->render();
        return response()->json(['viewData' => $viewData]);
    }

    public function alluser() {
        $users = User::count();
        //dd($users);
        return view('admin.dashboard')->with(['users' => $users]);
    }

}

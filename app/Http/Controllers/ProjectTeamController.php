<?php

namespace App\Http\Controllers;

use Validator;
use App\ProjectTeam;
use App\Project;
use App\User;
use App\ProjectRole;
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use Illuminate\Http\Request;

class ProjectTeamController extends Controller {

    private $controller = 'ProjectTeam';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        
        $targetArr = ProjectTeam::join('project', 'project.id','=','project_team.project_id')
                ->join('users', 'users.id','=','project_team.member_id')
                ->join('project_role', 'project_role.id','=','project_team.role_id')
                ->select('project_team.id',DB::raw("CONCAT(users.first_name,' ',users.last_name) AS name"),'project.name as project_name','project_role.name as role_name','project_team.engagement_date');
               
       //begin filtering
        $member_id = $request->member;
        $project_id = $request->project;
//        echo $searchText;exit;
        $memberArr =['0'=>__('label.SELECT_TEAM_MEMBER')]+User::where('group_id', '2')->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->pluck('name','id')->toArray();
        $projectArr =['0'=>__('label.SELECT_PROJECT')]+Project::pluck('name','id')->toArray();

        
        if (!empty($member_id)) {
            $targetArr->where(function ($query) use ($member_id) {
                $query->where('project_team.member_id',$member_id);
            });
        }

        if (!empty($project_id)) {
            $targetArr = $targetArr->where('project_team.project_id', '=',$project_id);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isNotEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/projectTeam?page=' . $page);
        }
        return view('team.index')->with(compact('targetArr', 'qpArr', 'status', 'memberArr','projectArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $projects = ['0' => __('label.SELECT_PROJECT')] + Project::pluck('name', 'id')->toArray();

        $members = ['0' => __('label.SELECT_TEAM_MEMBER')] + User::where('group_id', '2')
                        ->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'id')
                        ->pluck('name', 'id')->toArray();
        $roles = ['0' => __('label.SELECT_ROLE')] + ProjectRole::pluck('name', 'id')->toArray();
//        Helper::dump($roles);
        return view('team.create')->with(compact('qpArr', 'projects', 'members', 'roles'));
    }

    public function store(Request $request) {
//        Helper::dump($request->all());
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $rules = [
            'project_id' => 'required|not_in:0',
            'member_id' => 'required|not_in:0',
            'role_id' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('projectTeam/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new ProjectTeam;
        $target->project_id = $request->project_id;
        $target->member_id = $request->member_id;
        $target->role_id = $request->role_id;
        $target->engagement_date = !empty($request->engagement_date) ? $request->engagement_date :'';
        if ($target->save()) {
            Session::flash('success', __('label.TEAM_CREATED_SUCCESSFULLY'));
            return redirect('projectTeam');
        } else {
            Session::flash('error', __('label.TEAM_COULD_NOT_BE_CREATED'));
            return redirect('projectTeam/create' . $pageNumber);
        }
    }

    public function edit(Request $request) {
        $target = ProjectTeam::findOrFail($request->id);
//        Helper::dump($target->toArray());
        $projects = ['0' => __('label.SELECT_PROJECT')] + Project::pluck('name', 'id')->toArray();

        $members = ['0' => __('label.SELECT_TEAM_MEMBER')] + User::where('group_id', '2')
                        ->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'id')
                        ->pluck('name', 'id')->toArray();
        $roles = ['0' => __('label.SELECT_ROLE')] + ProjectRole::pluck('name', 'id')->toArray();
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('projectTeam');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('team.edit')->with(compact('target', 'qpArr','projects', 'members', 'roles'));
    }

    public function update(Request $request) {
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
         $rules = [
            'project_id' => 'required|not_in:0',
            'member_id' => 'required|not_in:0',
            'role_id' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('projectTeam/' . $request->id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = ProjectTeam::findOrFail($request->id);
        $target->project_id = $request->project_id;
        $target->member_id = $request->member_id;
        $target->role_id = $request->role_id;
        $target->engagement_date = !empty($request->engagement_date) ? $request->engagement_date :'';
        if ($target->save()) {
            Session::flash('success', __('label.TEAM_UPDATED_SUCCESSFULLY'));
            return redirect('projectTeam' . $pageNumber);
        } else {
            Session::flash('error', __('label.TEAM_NOT_UPDATED_SUCCESSFULLY'));
            return redirect('projectTeam/' . $request->id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request) {
        $target = ProjectTeam::findOrFail($request->id);
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            Session::flash('success', __('label.TEAM_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.TEAM_COULD_NOT_BE_DELETED'));
        }
        return redirect('projectTeam' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'member=' . $request->member_id. '&project=' . $request->project_id;
        return Redirect::to('projectTeam?' . $url);
    }

}

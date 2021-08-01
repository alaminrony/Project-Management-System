<?php

namespace App\Http\Controllers;

use Validator;
use App\BugResolution;
use App\BugFollowUp;
use App\Bug;
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use App\User;
use App\SupportTeam;
use Illuminate\Http\Request;

class BugResolutionController extends Controller {

    private $controller = 'BugResolution';

    public function index(Request $request) {
        $qpArr = $request->all();
        $bugClosedStatus = BugFollowUp::select('bug_id', 'status')->where('status', '3')->get();

        $followUpstatusArr = [];
        foreach ($bugClosedStatus as $closedStatus) {
            $followUpstatusArr[$closedStatus->bug_id] = $closedStatus->status;
        }
        // Employee id for getting re-assigned by name.
        $employeeArr = User::where('group_id', '2')->select('id', DB::raw("CONCAT(first_name,' ',last_name) as name"))->pluck('name', 'id')->toArray();


        $targetArr = Bug::join('project', 'project.id', '=', 'bug.project_id')
                ->leftjoin('users', 'users.id', '=', 'bug.locked_by')
                ->join('support_team', 'support_team.project_id', '=', 'bug.project_id')
                ->select('bug.id', 'bug.bug_title', 'bug.unique_code', 'bug.files', 'bug.locked_at'
                , 'bug.locked_by', 'bug.reassigned_by', 'bug.status', 'bug.reporting_date', 'project.name as projectName'
                , DB::raw("CONCAT(users.first_name,' ',users.last_name) as lockedByName")
                , 'support_team.team_manager_id', 'support_team.support_persons_id');


        $searchText = $request->search;
        $nameArr = Bug::select('bug_title')->get();
//        Helper::dump($nameArr->toarray());
        $status = [null => __('label.SELECT_STATUS_OPT'), '1' => __('label.OPEN'), '2' => __('label.CLOSE')];

        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('bug.bug_title', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('bug.status', '=', $request->status);
        }
        //end filtering
        $targetArr = $targetArr->orderBy('bug.reporting_date', 'desc')->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/bugResolution?page=' . $page);
        }
        return view('bugResolution.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr', 'followUpstatusArr', 'mergeSupportMemberArr', 'employeeArr'));
    }

    public function changeStatus(Request $request) {
        $target = Bug::findOrFail($request->bug_id);
        $target->status = !empty($request->bug_id && $request->bug_unlock == true) ? '1' : '2';
        $target->locked_by = !empty($request->bug_id && $request->bug_lock == true) ? Auth::user()->id : 0;
        $target->locked_at = date('Y-m-d H:i:s');
        if ($target->save()) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    public function viewDetails(Request $request) {
        $target = Bug::join('project', 'project.id', '=', 'bug.project_id')
                        ->join('reporting_medium', 'reporting_medium.id', '=', 'bug.reporting_medium')
                        ->join('contact', 'contact.id', '=', 'bug.reported_by')
                        ->join('bug_category', 'bug_category.id', '=', 'bug.severity_level')
                        ->select('bug.id', 'bug.bug_title', 'project.name as project_name', 'bug.files', 'bug.reporting_date', 'reporting_medium.medium_name', DB::raw("CONCAT(contact.first_name,' ',contact.last_name) as reported_by"), 'bug_category.category_level', 'bug.status', 'bug.unique_code')
                        ->where('bug.id', $request->id)->first();

        $latestProgress = BugFollowUp::select('progress')->where(['bug_id' => $request->id, 'status' => '1'])->latest()->take(1)->first();
        $followUps = BugFollowUp::join('users', 'users.id', '=', 'bug_followup.action_taken_by')
                        ->select('bug_followup.id', 'bug_followup.date_time', 'bug_followup.status', 'bug_followup.remarks', 'bug_followup.progress', DB::raw("CONCAT(first_name,' ',last_name) as action_taken_by"))
                        ->where('bug_id', $request->id)->orderBy('date_time', 'desc')->get();
        $viewBug = view('bugResolution.viewBugModal')->with(compact('target', 'followUps', 'latestProgress'))->render();
        return response()->json(['viewBug' => $viewBug]);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('bugResolution?' . $url);
    }

    public function bugReassigned(Request $request) {
        $managerAndSuppMember = Bug::join('support_team', 'support_team.project_id', '=', 'bug.project_id')
                        ->select('support_team.team_manager_id', 'support_team.support_persons_id')
                        ->where('bug.id', '=', $request->bug_id)->first();

        $managerID = $managerAndSuppMember->team_manager_id;
        $supportMemberArr = json_decode($managerAndSuppMember->support_persons_id);

        $suppPersonsName = User::select('id', DB::raw("CONCAT(users.first_name,' ',users.last_name) AS name"))->whereIn('id', $supportMemberArr)->get();
        $managerName = User::select('id', DB::raw("CONCAT(users.first_name,' ',users.last_name) AS name"))->where('id', $managerID)->get()->toArray();

        $supportPersonArr = [];
        foreach ($suppPersonsName as $key => $suppPersonName) {
            $supportPersonArr[$key]['id'] = $suppPersonName->id;
            $supportPersonArr[$key]['name'] = $suppPersonName->name;
        }

        $mamberAndSupportPerson = array_merge($supportPersonArr, $managerName);

        $personArr = ['' => __('label.SELECT_MEMBER')] + [];
        if (!empty($mamberAndSupportPerson)) {
            foreach ($mamberAndSupportPerson as $person) {
                if (Auth::user()->id != $person['id']) {
                    $personArr[$person['id']] = $person['name'];
                }
            }
        }
        $bugID = $request->bug_id;
        $viewData = view('bugResolution.viewReassignedPerson')->with(compact('personArr', 'bugID'))->render();
        return response()->json(['viewData' => $viewData]);
    }

    public function getMemberAndBugData(Request $request) {
        $userInfo = User::join('department', 'department.id', '=', 'users.department_id')
                        ->join('designation', 'designation.id', '=', 'users.designation_id')
                        ->select('users.id', 'users.first_name', 'users.last_name'
                                , 'users.photo', 'designation.name as designation_name'
                                , 'department.name as department_name', 'users.employee_id')
                        ->where('users.id', '=', $request->member_id)->first();

        $bugDetails = Bug::join('project', 'project.id', '=', 'bug.project_id')
                        ->join('reporting_medium', 'reporting_medium.id', '=', 'bug.reporting_medium')
                        ->join('contact', 'contact.id', '=', 'bug.reported_by')
                        ->join('bug_category', 'bug_category.id', '=', 'bug.severity_level')
                        ->select('bug.id', 'bug.bug_title', 'project.name as project_name', 'bug.files', 'bug.reporting_date', 'reporting_medium.medium_name', DB::raw("CONCAT(contact.first_name,' ',contact.last_name) as reported_by"), 'bug_category.category_level', 'bug.status', 'bug.unique_code')
                        ->where('bug.id', $request->bug_id)->first();
        
        $latestProgress = BugFollowUp::select('progress')->where(['bug_id' => $request->bug_id, 'status' => '1'])->latest()->take(1)->first();
        $viewData = view('bugResolution.memberInfo')->with(compact('userInfo','bugDetails','latestProgress'))->render();
        return response()->json(['viewData' => $viewData]);
    }

    public function updateLockedBy(Request $request) {
        $rules = [
            'reassigned_id' => ['required'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $target = Bug::findOrFail($request->bug_id);
        $target->locked_by = $request->reassigned_id;
        $target->reassigned_by = Auth::user()->id;

        if ($target->save()) {
            return response()->json(['success']);
        }
    }

}

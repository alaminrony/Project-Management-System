<?php

namespace App\Http\Controllers;

use Validator;
use App\ReportingMedium;
use App\PointOfContact;
use App\ProjectTeam;
use App\SupportTeam;
use App\Bug;
use App\BugCategory;
use App\Project;
use App\Contact;
use DB;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class SupportTeamController extends Controller {

    private $controller = 'SupportTeam';

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $projectList = ['0' => __('label.SELECT_PROJECT')] + Project::pluck('name', 'id')->toArray();
        
        return view('supportTeam.create')->with(compact('qpArr', 'projectList'));
    }

    public function getSupportedPerson(Request $request) {
        $supMembers = ProjectTeam::join('users', 'users.id', '=', 'project_team.member_id')
                ->join('project_role', 'project_role.id', '=', 'project_team.role_id')
                ->where('project_team.project_id', '=', $request->project_id)
                ->select('project_team.member_id as id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as name"), 'project_role.name as role', 'users.photo as image')
                ->get();
//        Helper::dump($supMembers->toArray());
        $checkedSupportMember = SupportTeam::where('project_id',$request->project_id)->select('team_manager_id','support_persons_id')->first();
//        Helper::dump(json_decode($checkedSupportMember->support_persons_id));
        $viewData = view('supportTeam.supportMember')->with(compact('supMembers','checkedSupportMember'))->render();
        return response()->json(['viewData' => $viewData]);
    }

    public function store(Request $request) {
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

//        $errors=[];
//        
//        if(empty($request->project_id)){
//            $errors[]='the ';
//        }
        
        $rules = [
            'project_id' => 'required|not_in:0',
            'support_persons_id' => 'required',
            'team_manager_id' => 'required',
        ];
        $message = [
            'project_id.required'=>'Please Select Project',
            'support_persons_id.required'=>'Please Checked Supported Person',
            'team_manager_id.required'=>'Please Checked Supported Manager',
        ];
        
//        $supportPersonsArr = $request->support_persons_id;
//        if(in_array($request->team_manager_id,$supportPersonsArr)){
//            $message = [
//            'project_id.required'=>'Please Select Project',
//        ];
//        }
        $validator = Validator::make($request->all(), $rules,$message);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
//        if (!empty($errors)) {
//            return response()->json(['errors' => $errors]);
//        }
        $delPreData = SupportTeam::where('project_id',$request->project_id)->delete();
        $target = new SupportTeam;
        $target->project_id = $request->project_id;
        $target->support_persons_id = json_encode($request->support_persons_id);
        $target->team_manager_id = $request->team_manager_id;

        if ($target->save()) {
            return response()->json('success');
        } else {
           return response()->json('errors');
        }
    }
    
   
   
}

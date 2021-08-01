<?php

namespace App\Http\Controllers;

use Validator;
use App\BugFollowUp;
use App\Bug;
use App\Project;
use App\User;
use DB;
use Auth;
use Session;
use Redirect;
use Helper;
use PDF;
use Illuminate\Http\Request;

class EmployeeReportController extends Controller {

    private $controller = 'EmployeeReport';

    public function index(Request $request) {
        $qpArr = $request->all();
        $generate = $request->generate;

        $followUpEmployee = BugFollowUp::select('action_taken_by')->distinct()->get();

        $followUpEmployeeArr = [];
        if ($followUpEmployee->isNotEmpty()) {
            foreach ($followUpEmployee as $employee) {
                $followUpEmployeeArr[] = $employee->action_taken_by;
            }
        }

        $employee = ['' => __('label.SELECT_EMPLOYEE')] + User::select('id', DB::raw("CONCAT(first_name,' ',last_name) as name"))
                        ->where('group_id', '2')
                        ->whereIn('id', $followUpEmployeeArr)
                        ->pluck('name', 'id')
                        ->toArray();

        if ($request->generate == 'true') {
            $targetArr = BugFollowUp::join('bug', 'bug.id', '=', 'bug_followup.bug_id')
                    ->join('project', 'project.id', '=', 'bug.project_id')
                    ->join('users','users.id','=','bug_followup.action_taken_by')
                    ->select('bug.id', 'bug.bug_title', 'bug_followup.remarks', 'bug_followup.progress', 'bug_followup.date_time', 'bug_followup.status',
                            'project.name');


            if (!empty($request->fromDate) && !empty($request->toDate)) {
                $targetArr = $targetArr->whereBetween('bug_followup.date_time', ["{$request->fromDate} 00:00:00","{$request->toDate} 23:59:59"]);
            }

            if (!empty($request->employeeId)) {
                $targetArr = $targetArr->where('bug_followup.action_taken_by', $request->employeeId);
            }

            if ($request->view == 'print') {
                $targetArr = $targetArr->orderBy('bug_followup.bug_id', 'asc')->get();
                return view('employeeReport.printEmployeeReport')->with(compact('targetArr', 'request', 'employee'));
            } else if ($request->view == 'pdf') {
                $targetArr = $targetArr->orderBy('bug_followup.bug_id', 'asc')->get();
                $pdf = PDF::loadView('employeeReport.printEmployeeReport', compact('targetArr', 'request','employee'))
                        ->setPaper('a4', 'portrait')
                        ->setOptions(['defaultFont' => 'sans-serif']);
                return $pdf->download('employeeReport.pdf');
            }

            $targetArr = $targetArr->orderBy('bug_followup.bug_id', 'asc')->get();
        }

        return view('employeeReport.index')->with(compact('targetArr', 'qpArr','employee','generate'));
    }

    public function filter(Request $request) {
        $rules = [
            'from_date' => 'required_with:to_date',
            'to_date' => 'required_with:from_date',
        ];
       
        if (!empty($request->from_date) && !empty($request->to_date)) {
            if ($request->from_date > $request->to_date) {
                $rules = [
                    'to_date' => ['after_or_equal:from_date'],
                ];
            }
        }

        $validator = Validator::make($request->all(), $rules);
        $url = 'fromDate=' . $request->from_date.
                '&toDate=' . $request->to_date.
                '&employeeId=' . $request->employee_id;


        if ($validator->fails()) {
            return redirect('employeeReport?' . $url)->withErrors($validator);
        }
//        elseif (!empty($errors)) {
//            return redirect('bugReport?' . $url)->withErrors($errors);
//        }
        return Redirect::to('employeeReport?generate=true&' . $url);
    }

}

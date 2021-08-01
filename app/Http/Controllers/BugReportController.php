<?php

namespace App\Http\Controllers;

use Validator;
use App\ReportingMedium;
use App\PointOfContact;
use App\Bug;
use App\BugCategory;
use App\Project;
use App\Contact;
use DB;
use Auth;
use Session;
use Redirect;
use Helper;
use App\ProductPhoto;
use App\Product;
use PDF;
use Illuminate\Http\Request;

class BugReportController extends Controller {

    private $controller = 'BugReport';

    public function index(Request $request) {
//        Helper::dump($request->all());
        $qpArr = $request->all();
        $generate = $request->generate;

        $projects = ['' => __('label.SELECT_PROJECT')] + Project::join('bug', 'bug.project_id', '=', 'project.id')
                        ->select('project.id as id', 'project.name as name')
                        ->pluck('name', 'id')
                        ->toArray();
        

        $createdBy = ['' => __('label.SELECT_CREATED_BY')] + Bug::join('users', 'users.id', '=', 'bug.created_by')
                        ->select('users.id as id', DB::raw("CONCAT(first_name,' ',last_name) as name"))
                        ->pluck('name', 'id')
                        ->toArray();

        if ($request->generate == 'true') {
            $targetArr = Bug::join('project', 'project.id', '=', 'bug.project_id')
                    ->join('reporting_medium', 'reporting_medium.id', '=', 'bug.reporting_medium')
                    ->join('contact', 'contact.id', '=', 'bug.reported_by')
                    ->join('bug_category', 'bug_category.id', '=', 'bug.severity_level')
                    ->join('users', 'users.id', '=', 'bug.created_by')
                    ->select('bug.id', 'bug.bug_title', 'bug.project_id', 'bug.created_by', 'project.name as project_name', 'bug.files', 'bug.reporting_date', 'reporting_medium.medium_name', DB::raw("CONCAT(contact.first_name,' ',contact.last_name) as reported_by"), 'bug_category.category_level', 'bug.status', 'bug.unique_code', DB::raw("CONCAT(users.first_name,' ',users.last_name) as created_by"));


            //Helper::dump($targetArr->get()->toArray());
            if (!empty($request->fromDate) && !empty($request->toDate)) {
                $targetArr = $targetArr->whereBetween('bug.reporting_date', [$request->fromDate, $request->toDate]);
            }

            if (!empty($request->projectId)) {
                $targetArr = $targetArr->where('bug.project_id', $request->projectId);
            }

            if (!empty($request->createdBy)) {
                $targetArr = $targetArr->where('bug.created_by', '=', $request->createdBy);
            }

            if ($request->view == 'print') {
                $targetArr = $targetArr->orderBy('id', 'asc')->get();
                return view('bugReport.printBugReport')->with(compact('targetArr','request','projects','createdBy'));
            } 
            else if ($request->view == 'pdf') {
                $targetArr = $targetArr->orderBy('id', 'asc')->get();
                $pdf = PDF::loadView('bugReport.printBugReport', compact('targetArr','request','projects','createdBy'))
                        ->setPaper('a4', 'portrait')
                        ->setOptions(['defaultFont' => 'sans-serif']);
                return $pdf->download('Bug_Report.pdf');
            }

            $targetArr = $targetArr->orderBy('id', 'asc')->get();
        }

        return view('bugReport.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr', 'projects', 'createdBy', 'generate'));
    }

    public function filter(Request $request) {
        $rules = [
            'from_date' => 'required_with:to_date',
            'to_date' => 'required_with:from_date',
        ];
        $errors =[];
        if (!empty($request->from_date) && !empty($request->to_date)) {
            if ($request->from_date > $request->to_date) {
                $rules = [
                    'to_date' => ['after_or_equal:from_date'],
                ];
            }
        }
        
        elseif (empty($request->from_date) && empty($request->to_date) && empty($request->project_id) && empty($request->created_by)) {
            $errors['all-empty'] = 'Please Given At Least 1 field';
        }

        $validator = Validator::make($request->all(), $rules);
        $url = 'fromDate=' . $request->from_date
                . '&toDate=' . $request->to_date . '&projectId=' . $request->project_id
                . '&createdBy=' . $request->created_by;
        
        
        if ($validator->fails()) {
            return redirect('bugReport?' . $url)->withErrors($validator);
        } 
//        elseif (!empty($errors)) {
//            return redirect('bugReport?' . $url)->withErrors($errors);
//        }
        return Redirect::to('bugReport?generate=true&' . $url);
    }

}

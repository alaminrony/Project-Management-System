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
use Illuminate\Http\Request;

class BugController extends Controller {

    private $controller = 'Bug';

    public function index(Request $request) {
//        Helper::dump($request->all());
        //passing param for custom function
        $qpArr = $request->all();
        $targetArr = Bug::join('project', 'project.id', '=', 'bug.project_id')
                ->join('reporting_medium', 'reporting_medium.id', '=', 'bug.reporting_medium')
                ->join('contact', 'contact.id', '=', 'bug.reported_by')
                ->join('bug_category', 'bug_category.id', '=', 'bug.severity_level')
                ->select('bug.id', 'bug.bug_title', 'project.name as project_name', 'bug.files', 'bug.reporting_date', 'reporting_medium.medium_name', DB::raw("CONCAT(contact.first_name,' ',contact.last_name) as reported_by"), 'bug_category.category_level', 'bug.status', 'bug.unique_code');

        $bugTitleArr = ['' => __('label.SELECT_BUG_TITLE')] + Bug::pluck('bug_title', 'id')->toArray();
        $bugProjectArr = ['' => __('label.SELECT_PROJECT')] + Bug::join('project', 'project.id', '=', 'bug.project_id')
                        ->select('project.id', 'project.name')->pluck('name', 'id')->toArray();

        if (!empty($request->title_id)) {
            $targetArr = $targetArr->where('bug.id', '=', $request->title_id);
        }
        if (!empty($request->project_id)) {
            $targetArr = $targetArr->where('bug.project_id', '=', $request->project_id);
        }
        //end filtering
        $targetArr = $targetArr->orderBy('id', 'asc')->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isNotEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/bug?page=' . $page);
        }
        return view('bug.index')->with(compact('targetArr', 'qpArr', 'status', 'bugTitleArr', 'bugProjectArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $projectList = ['0' => __('label.SELECT_PROJECT')] + Project::pluck('name', 'id')->toArray();
        $mediumList = ['0' => __('label.SELECT_REPORTING_MEDIUM')] + ReportingMedium::pluck('medium_name', 'id')->toArray();
        $bugCatList = ['0' => __('label.SELECT_SEVERITY_LEVEL')] + BugCategory::pluck('category_level', 'id')->toArray();
        $reportByList = ['0' => __('label.SELECT_REPORTED_BY')];
//      Helper::dump($bugCatList);
        return view('bug.create')->with(compact('qpArr', 'projectList', 'mediumList', 'bugCatList', 'reportByList'));
    }

    public function getReportedBy(Request $request) {
        $reportByProject = PointOfContact::where('project_id', $request->project_id)->select('poc_level_1', 'poc_level_2', 'poc_level_3')->get();
        $cotactIdArr = [];
        if ($reportByProject->isNotEmpty()) {
            foreach ($reportByProject as $iteam) {
                $cotactIdArr[$iteam->poc_level_1] = $iteam->poc_level_1;
                $cotactIdArr[$iteam->poc_level_2] = $iteam->poc_level_2;
                $cotactIdArr[$iteam->poc_level_3] = $iteam->poc_level_3;
            }
        }
        $reportByList = ['0' => __('label.SELECT_REPORTED_BY')] + Contact::whereIn('id', $cotactIdArr)->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'id')->pluck('name', 'id')->toArray();
        $viewReportedBy = view('bug.reportedBy')->with(compact('reportByList'))->render();
        return response()->json(['viewReportedBy' => $viewReportedBy]);
    }

    public function viewDetails(Request $request) {
        $target = Bug::join('project', 'project.id', '=', 'bug.project_id')
                        ->join('reporting_medium', 'reporting_medium.id', '=', 'bug.reporting_medium')
                        ->join('contact', 'contact.id', '=', 'bug.reported_by')
                        ->join('bug_category', 'bug_category.id', '=', 'bug.severity_level')
                        ->select('bug.id', 'bug.bug_title', 'project.name as project_name', 'bug.files', 'bug.reporting_date', 'reporting_medium.medium_name', DB::raw("CONCAT(contact.first_name,' ',contact.last_name) as reported_by"), 'bug_category.category_level', 'bug.status', 'bug.unique_code')
                        ->where('bug.id', $request->id)->first();
        $viewBug = view('bug.viewBugModal')->with(compact('target'))->render();
        return response()->json(['viewBug' => $viewBug]);
    }

    public function store(Request $request) {
//        Helper::dump($request->title);
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $fileArr = [];
        if ($files = $request->file('files')) {
            foreach ($files as $key => $val) {
                $imagePath = 'public/uploads/bug/';
                $imageName = uniqid() . "." . date('Ymd') . "." . $val->getClientOriginalExtension();
                $val->move($imagePath, $imageName);
                $fileArr[$key] = $imageName;
            }
        }

        $titleArr = [];
        foreach ($request->title as $key => $val) {
            $titleArr[$key]['title'] = $val;
            $titleArr[$key]['file'] = !empty($fileArr[$key]) ? $fileArr[$key] : null;
        }

        $rules = [
            'bug_title' => 'required',
            'project_id' => 'required|not_in:0',
            'reporting_date' => 'required',
            'reporting_medium' => 'required|not_in:0',
            'reported_by' => 'required|not_in:0',
            'severity_level' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('bug/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        //Generating a random Unique Bug Tracking Code
        $str = "";
        $characters = array_merge(range('A', 'Z'), range('0', '9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < 8; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }


        $data = [
            'bug_title' => $request->bug_title,
            'project_id' => $request->project_id,
            'reporting_date' => $request->reporting_date,
            'reporting_medium' => $request->reporting_medium,
            'reported_by' => $request->reported_by,
            'severity_level' => $request->severity_level,
            'files' => json_encode($titleArr),
            'unique_code' => $str,
            'created_at' => date('Y-m-d'),
            'created_by' => Auth::user()->id,
            'updated_at' => date('Y-m-d'),
            'updated_by' => Auth::user()->id,
        ];

        if (Bug::insert($data)) {
            Session::flash('success', __('label.BUG_CREATED_SUCCESSFULLY'));
            return redirect('bug');
        } else {
            Session::flash('error', __('label.BUG_COULD_NOT_BE_CREATED'));
            return redirect('bug/create' .
                    $pageNumber);
        }
    }

    public function edit(Request $request) {
        $target = Bug::findOrFail($request->id);
        $projectList = ['0' => __('label.SELECT_PROJECT')] + Project::pluck('name', 'id')->toArray();
        $mediumList = ['0' => __('label.SELECT_REPORTING_MEDIUM')] + ReportingMedium::pluck('medium_name', 'id')->toArray();
        $bugCatList = ['0' => __('label.SELECT_SEVERITY_LEVEL')] + BugCategory::pluck('category_level', 'id')->toArray();
        $reportByList = ['0' => __('label.SELECT_REPORTED_BY')] + Contact::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'id')->pluck('name', 'id')->toArray();

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('bug');
        }
        //passing param for custom function
        $qpArr = $request->all();
        return view('bug.edit')->with(compact('target', 'qpArr', 'projectList', 'mediumList', 'bugCatList', 'reportByList'));
    }

    public function update(Request $request) {
//        Helper::dump($request->all());
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];

        $target = Bug::findOrFail($request->id);

        //find previous file name
        $preFileArr = json_decode($target->files);
        $preFileName = [];
        foreach ($preFileArr as $key => $preFile) {
            $preFileName[$key] = $preFile->file;
        }

        //appending and change existing file should be here
//        if (file_exists('public/uploads/bug/' . $preFileName[$key])) {
//                    unlink('public/uploads/bug/' . $preFileName[$key]);
//                }
        $fileArr = [];
        if ($files = $request->file('files')) {
            foreach ($files as $key => $val) {
                $imagePath = 'public/uploads/bug/';
                $imageName = uniqid() . "." . date('Ymd') . "." . $val->getClientOriginalExtension();
                $val->move($imagePath, $imageName);
                $fileArr[$key] = $imageName;
            }
        }


        // if change existing file then it will replace previous file name into new one.
        $replaceExistingFile = array_replace_recursive($preFileName, $fileArr);
        $realFileArr = [];
        foreach ($replaceExistingFile as $key => $replaceFile) {
            $realFileArr[$key] = $replaceFile;
        }

        //foreach respect to file
        $titleArr = [];
        foreach ($request->title as $key => $val) {
            $titleArr[$key]['title'] = $val;
            $titleArr[$key]['file'] = !empty($realFileArr[$key]) ? $realFileArr[$key] : null;
        }

        $rules = [
            'bug_title' => 'required',
            'project_id' => 'required|not_in:0',
            'title' => 'required',
            'reporting_date' => 'required',
            'reporting_medium' => 'required|not_in:0',
            'reported_by' => 'required|not_in:0',
            'severity_level' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('bug/' . $request->id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

//        $target = Bug::findOrFail($request->id);
        $target->bug_title = $request->bug_title;
        $target->project_id = $request->project_id;
        $target->reporting_date = $request->reporting_date;
        $target->reporting_medium = $request->reporting_medium;
        $target->reported_by = $request->reported_by;
        $target->severity_level = $request->severity_level;

        $target->files = json_encode($titleArr);
        if ($target->save()) {
            Session::flash('success', __('label.BUG_UPDATED_SUCCESSFULLY'));
            return redirect('bug' . $pageNumber);
        } else {
            Session::flash('error', __('label.BUG_COULD_NOT_BE_UPDATED'));
            return redirect('bug/' . $request->id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request) {
        $target = Bug::findOrFail($request->id);

        //previous Image Deleted
        $preImages = json_decode($target->files);

        if (!empty($preImages)) {
            foreach ($preImages as $preImage) {
                if (file_exists('public/uploads/bug/' . $preImage->file) && !empty($preImage->file)) {
                    unlink('public/uploads/bug/' . $preImage->file);
                }
            }
        }
        //previous Image Deleted   

        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            Session::flash('success', __('label.BUG_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.BUG_COULD_NOT_BE_DELETED'));
        }
        return redirect('bug' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'title_id=' . $request->title_id . '&project_id=' . $request->project_id;
        return Redirect::to('bug?' . $url);
    }

}

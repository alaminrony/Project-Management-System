<?php

namespace App\Http\Controllers;

use Validator;
use App\ReportingMedium;
use App\PointOfContact;
use App\Project;
use App\Contact;
use DB;
use Auth;
use Session;
use Redirect;
use Helper;
use Illuminate\Http\Request;

class PointOfContactController extends Controller {

    private $controller = 'PointOfContact';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = PointOfContact::join('project','project.id','=','point_of_contact.project_id')
                ->select('point_of_contact.id','project.name as project_name','point_of_contact.poc_level_1',
                        'point_of_contact.poc_level_2','point_of_contact.poc_level_3','point_of_contact.status');
        //POC comes from Contact Table
        $contactArr = Contact::select('id',DB::raw("CONCAT(first_name,' ',last_name) AS contact_name"))->get();
//        Helper::dump($pocArr->toArray());

        //begin filtering
        $searchText = $request->search;
        //echo $searchText;
        $nameArr = Project::select('name as project_name')->get();
        $status = [null => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive'];
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('project.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('point_of_contact.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isNotEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/pointOfContact?page=' . $page);
        }
        return view('pointOfContact.index')->with(compact('targetArr','contactArr', 'qpArr', 'status', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $projectList = ['0'=>__('label.SELECT_PROJECT')]+Project::pluck('name','id')->toArray();
        $clientList = [''=>__('label.SELECT_CLIENT')]+Contact::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->pluck('name','id')->toArray();
//        Helper::dump($clientList);
        return view('pointOfContact.create')->with(compact('qpArr', 'projectList','clientList'));
    }

    public function store(Request $request) {
//        Helper::dump($request->all());
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $rules = [
            'project_id' => 'required|not_in:0',
            'poc_level_1' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('pointOfContact/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new PointOfContact;
        $target->project_id  = $request->project_id;
        $target->poc_level_1 = $request->poc_level_1;
        $target->poc_level_2 = $request->poc_level_2;
        $target->poc_level_3 = $request->poc_level_3;
        $target->status = !empty($request->status) ? $request->status : '';
        if ($target->save()) {
            Session::flash('success', __('label.POINT_OF_CONTACT_CREATED_SUCCESSFULLY'));
            return redirect('pointOfContact');
        } else {
            Session::flash('error', __('label.POINT_OF_CONTACT_COULD_NOT_BE_CREATED'));
            return redirect('pointOfContact/create' . $pageNumber);
        }
    }

    public function edit(Request $request) {
        $target = PointOfContact::findOrFail($request->id);
//        Helper::dump($target->project_id);
        $projectList = [''=>__('label.SELECT_PROJECT')]+Project::pluck('name','id')->toArray();
//        Helper::dump($projectList);
        $clientList = [''=>__('label.SELECT_CLIENT')]+Contact::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->pluck('name','id')->toArray();
//        Helper::dump($target->toArray());
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('pointOfContact');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('pointOfContact.edit')->with(compact('target', 'qpArr','projectList','clientList'));
    }

    public function update(Request $request) {
//        Helper::dump($request->all());
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        $rules = [
            'project_id' => 'required|not_in:0',
            'poc_level_1' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('pointOfContact/' . $request->id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = PointOfContact::findOrFail($request->id);
        $target->project_id  = $request->project_id;
        $target->poc_level_1 = $request->poc_level_1;
        $target->poc_level_2 = $request->poc_level_2;
        $target->poc_level_3 = $request->poc_level_3;
        $target->status = $request->status;
        if ($target->save()) {
            Session::flash('success', __('label.POINT_OF_CONTACT_UPDATED_SUCCESSFULLY'));
            return redirect('pointOfContact' . $pageNumber);
        } else {
            Session::flash('error', __('label.POINT_OF_CONTACT_COULD_NOT_BE_UPDATED'));
            return redirect('pointOfContact/' . $request->id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request) {
        $target = PointOfContact::findOrFail($request->id);
//        Helper::dump($target->toArray());
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            Session::flash('success', __('label.POINT_OF_CONTACT_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.POINT_OF_CONTACT_COULD_NOT_BE_DELETED'));
        }
        return redirect('pointOfContact' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('pointOfContact?' . $url);
    }

}

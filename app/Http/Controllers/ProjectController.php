<?php

namespace App\Http\Controllers;

use Validator;
use App\Project;
use App\Company;
use App\ProjectStatus;
use App\TaskToProject;
use Auth;
use Session;
use Redirect;
use Helper;
use App\Industry;
use Illuminate\Http\Request;

class ProjectController extends Controller {

    private $controller = 'Project';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Project::join('company', 'company.id', '=', 'project.client_id')
                ->join('project_status', 'project.project_status', '=', 'project_status.id')
                ->select('project.id', 'project.name', 'company.name as company_name', 'project.tentative_date', 'project.dead_line', 'project.order', 'project.upload_file', 'project.status', 'project_status.name as status_name')
                ->orderBy('order', 'asc');

        //begin filtering
        $searchText = $request->search;
        //echo $searchText;
        $nameArr = Project::select('name')->get();
        $status = ['' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive'];
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('project.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('project.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isNotEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/project?page=' . $page);
        }
        // Helper::dump($projects->toArray());

        return view('project.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $clientList = ['' => __('label.SELECT_CLIENT')] + Company::orderBy('order')->pluck('name', 'id')->toArray();
        $projectStatusList = ['' => __('label.SELECT_PROJECT_STATUS')] + ProjectStatus::orderBy('order')->pluck('name', 'id')->toArray();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        return view('project.create')->with(compact('qpArr', 'orderList', 'clientList', 'projectStatusList'));
    }

    public function store(Request $request) {

        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $rules = [
            'name' => 'required',
            'order' => 'required|not_in:0',
            'client_id' => 'required',
            'project_status' => 'required',
        ];
        if (!empty($request->file('upload_file'))) {
            $rules = [
                'upload_file' => ['required', 'mimes:jpeg,png', 'max:2048'],
            ];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('project/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Project;
        $target->name = !empty($request->name) ? $request->name : '';
        $target->client_id = !empty($request->client_id) ? $request->client_id : '';
        $target->project_status = !empty($request->project_status) ? $request->project_status : '';
        $target->tentative_date = !empty($request->tentative_date) ? $request->tentative_date : '';
        $target->dead_line = !empty($request->dead_line) ? $request->dead_line : '';
        $target->order = !empty($request->order) ? $request->order : '';

        if ($files = $request->file('upload_file')) {
            $imagePath = 'public/image/';
            $imageName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
            $files->move($imagePath, $imageName);
            $target->upload_file = $imageName;
        } else {
            $target->upload_file = '';
        }

        $target->status = !empty($request->status) ? $request->status : '';
        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.PROJECT_CREATED_SUCCESSFULLY'));
            return redirect('project');
        } else {
            Session::flash('error', __('label.PROJECT_COULD_NOT_BE_CREATED'));
            return redirect('project/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Project::findOrFail($request->id);
//        Helper::dump($target->toArray());
        $projectStatusList = ['' => __('label.SELECT_PROJECT_STATUS')] + ProjectStatus::orderBy('order')->pluck('name', 'id')->toArray();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        $clientList = ['' => __('label.SELECT_CLIENT')] + Company::orderBy('order')->pluck('name', 'id')->toArray();
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('project');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('project.edit')->with(compact('target', 'qpArr', 'orderList', 'clientList', 'projectStatusList'));
    }

    public function update(Request $request) {
//        echo "<pre>"; print_r($request->all());exit;
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter'];
        $rules = [
            'name' => 'required',
            'order' => 'required|not_in:0',
            'client_id' => 'required',
            'project_status' => 'required',
        ];
        if (!empty($request->file('upload_file'))) {
            $rules = [
                'upload_file' => ['required', 'mimes:jpeg,png', 'max:2048'],
            ];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('project/' . $request->id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = Project::findOrFail($request->id);
        $presentOrder = $target->order;
        $target->name = $request->name;
        $target->client_id = !empty($request->client_id) ? $request->client_id : '';
        $target->project_status = !empty($request->project_status) ? $request->project_status : '';
        $target->tentative_date = !empty($request->tentative_date) ? $request->tentative_date : '';
        $target->dead_line = !empty($request->dead_line) ? $request->dead_line : '';
        $target->order = $request->order;

        if ($files = $request->file('upload_file')) {
            $imagePath = 'public/image/';
            $imageName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
            $files->move($imagePath, $imageName);
            $target->upload_file = $imageName;
        }
        $target->status = $request->status;
        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.PROJECT_UPDATED_SUCCESSFULLY'));
            return redirect('project' . $pageNumber);
        } else {
            Session::flash('error', __('label.PROJECT_COULD_NOT_BE_UPDATED'));
            return redirect('project/' . $request->id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request) {
//        Helper::dump($request->id);
        $target = Project::findOrFail($request->id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('success', __('label.PROJECT_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.PROJECT_COULD_NOT_BE_DELETED'));
        }
        return redirect('project' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('project?' . $url);
    }

    public function ganttChart(Request $request) {
        $chartData = TaskToProject::join('task', 'task_to_project.task_id', '=', 'task.id')
                ->select('task_to_project.task_id', 'task.title', 'task_to_project.start_time', 'task_to_project.end_time', 'task_to_project.prerequisite')
                ->where('task_to_project.project_id', $request->id)
                ->orderBy('task_to_project.task_id')
                ->get();
//        Helper::dump($chartData->toArray());
        return view('project.ganttChart')->with(compact('chartData'));
    }

}

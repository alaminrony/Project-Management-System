<?php

namespace App\Http\Controllers;

use Validator;
use App\Company;
use Auth;
use Session;
use Redirect;
use Helper;
use App\Industry;
use Illuminate\Http\Request;

class CompanyController extends Controller {

    private $controller = 'Company';

    public function index(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

//        $targetArr = Company::select('company.*')->orderBy('order', 'asc');
        $targetArr = Company::leftJoin('industry', 'company.industry', '=', 'industry.id')
                ->select('company.*', 'industry.name as industryName')
                ->orderBy('order', 'asc');


        //begin filtering
        $searchText = $request->search;
        //echo $searchText;
        $nameArr = Company::select('company.name')->get();
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('company.name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('company.short_name', 'LIKE', '%' . $searchText . '%');
            });
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('company.status', '=', $request->status);
        }
        //end filtering

        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/company?page=' . $page);
        }

        return view('company.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $industries = ['0' => __('label.SELECT_ORDER_OPT')] + Industry::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        return view('company.create')->with(compact('qpArr', 'orderList', 'industries'));
    }

    public function getCompanyType(Request $request) {
        $motherCompany = Company::where('type', '1')->get();
        if ($motherCompany->isNotEmpty()) {
            $output = '<div class="form-group">
                           <label class="control-label col-md-4" for="order">Mother Company:<span class="text-danger"></span></label>
                            <div class="col-md-8"> 
                            <select name="mother_company_id" class="form-control js-source-states">
                            <option value = "" selected = "disabled">--Select Mother Company-- </option>';
            foreach ($motherCompany as $company) {
                $output .= '<option value="' . $company['id'] . '">' . $company['name'] . '</option>';
            }
            $output .= '     </select>
                         </div>
                     </div>';
            echo $output;
        }
    }

    public function store(Request $request) {
//        echo "<pre>"; print_r($request->all()); exit;
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $rules = [
            'name' => 'required|unique:company',
            'short_name' => 'required|unique:company',
            'order' => 'required|not_in:0',
        ];
        if (!empty($request->file('logo'))) {
            $rules = [
                'logo' => ['required', 'image', 'mimes:jpeg,png', 'max:2048'],
            ];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('company/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Company;
        $target->name = $request->name;
        $target->short_name = $request->short_name;
        $target->address = $request->address;
        $target->contact_no = $request->contact_no;
        $target->email = $request->email;
        $target->industry = $request->industry;

        if ($files = $request->file('logo')) {
            $imagePath = 'public/image/';
            $imageName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
            $files->move($imagePath, $imageName);
            $target->logo = $imageName;
        }
        $target->type = $request->type;
        $target->mother_company_id = $request->mother_company_id;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.COMPANY_CREATED_SUCCESSFULLY'));
            return redirect('company');
        } else {
            Session::flash('error', __('label.COMPANY_COULD_NOT_BE_CREATED'));
            return redirect('company/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Company::find($id);
//        echo "<pre>";print_r($target->toArray());exit;
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        $industries = ['0' => __('label.SELECT_ORDER_OPT')] + Industry::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('company');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('company.edit')->with(compact('target', 'qpArr', 'orderList', 'industries', 'companyType'));
    }

    public function update(Request $request, $id) { //print_r($request->all());exit;
//        echo "<pre>"; print_r($request->all());exit;
        $target = Company::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter']; //!empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        $rules = [
            'name' => 'required',
            'short_name' => 'required',
            'order' => 'required|not_in:0',
        ];
        if (!empty($request->file('logo'))) {
            $rules = [
                'logo' => ['required', 'image', 'mimes:jpeg,png', 'max:2048'],
            ];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('company/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }


        $target->name = $request->name;
        $target->short_name = $request->short_name;
        $target->address = $request->address;
        $target->contact_no = $request->contact_no;
        $target->email = $request->email;
        $target->industry = $request->industry;

        if ($files = $request->file('logo')) {
            $imagePath = 'public/image/';
            $imageName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
            $files->move($imagePath, $imageName);
            $target->logo = $imageName;
        }
        $target->type = $request->type;
        $target->mother_company_id = $request->mother_company_id;
        $target->order = $request->order;
        $target->status = $request->status;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.COMPANY_UPDATED_SUCCESSFULLY'));
            return redirect('company' . $pageNumber);
        } else {
            Session::flash('error', __('label.COMPANY_COULD_NOT_BE_UPDATED'));
            return redirect('company/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Company::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.COMPANY_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.COMPANY_COULD_NOT_BE_DELETED'));
        }
        return redirect('company' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->search . '&status=' . $request->status;
        return Redirect::to('company?' . $url);
    }

}

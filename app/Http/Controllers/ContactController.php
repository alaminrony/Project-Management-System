<?php

namespace App\Http\Controllers;

use Validator;
use App\Contact;
use App\Occupation;
use App\Company;
use App\Designation;
use App\Speciality;
use App\Weakness;
use App\MeetUp;
use App\ContactAcademic;
use App\ContactProfessional;
use App\ContactRemarks;
use Auth;
use Session;
use Redirect;
use Helper;
use DB;
use Illuminate\Http\Request;

class ContactController extends Controller {

    private $controller = 'Contact';

    public function index(Request $request) {
        $companyId = $request->id;
        $qpArr = $request->all();
        $targetArr = Contact::Join('occupation', 'contact.occupation_id', '=', 'occupation.id')
                ->Join('company', 'contact.company_id', '=', 'company.id')
                ->Join('designation', 'contact.designation_id', '=', 'designation.id')
                ->select('contact.id', 'contact.first_name', 'contact.last_name', 'contact.contact_number',
                        'contact.email', 'contact.image', 'contact.status', 'occupation.name as occupation_name',
                        'occupation.name as occupation_name', 'company.name as company_name', 'designation.name as designation_name')
                ->where('contact.company_id',$request->id)
                ->orderBy('contact.id', 'desc');
        
        //company information Need to show Details company info in company/contact
        $companyInfo = Company::leftJoin('industry','industry.id','=','company.industry')
                ->select('company.name','company.short_name','company.address','company.contact_no',
                        'company.email','industry.name as industry_name','company.logo','company.type',
                        'company.mother_company_id','company.status','company.created_at')
                ->where('company.id',$companyId)->first();
        
        //All company name need to show mother company name in company/contact
        $companies = Company::select('id','name')->get();
        $companyArr = [];
        foreach($companies as $company){
            $companyArr[$company->id] = $company->name;
        }
//        Helper::dump($companyArr);

        $searchId = $request->search;
//        echo $searchId;exit;
        $nameArr = [''=>__('label.SELECT_NAME')]+Contact::select(DB::raw("CONCAT(first_name,' ',last_name) as name"),'id')->where('contact.company_id',$request->id)->pluck('name','id')->toArray();
//        Helper::dump($nameArr);
        $status = array('0' => __('label.SELECT_STATUS_OPT'), '1' => 'Active', '2' => 'Inactive');
        if (!empty($searchId)) {
            $targetArr = $targetArr->where('contact.id','=',$searchId);
        }
        if (!empty($request->status)) {
            $targetArr = $targetArr->where('contact.status', '=', $request->status);
        }
        //end filtering
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('company/'.$companyId.'/contact?page=' . $page);
        }
        return view('contact.index')->with(compact('targetArr', 'qpArr', 'status', 'nameArr', 'companyArr','companyId','companyInfo'));
    }

    public function viewContactNumber(Request $request) {
        $contactNumber = Contact::select('contact_number')->where('id', $request->id)->first();
        $contactArray = explode(',', $contactNumber->contact_number);
        $viewModal = view('contact.viewContactModal')->with(compact('contactArray'))->render();
        return response()->json(['viewModal' => $viewModal]);
    }

    public function viewContactEmail(Request $request) {
//        echo "<pre>";print_r($contactEmail->email);exit;
        $contactEmail = Contact::select('email')->where('id', $request->id)->first();
        $emailArray = explode(',', $contactEmail->email);
        $viewEmail = view('contact.viewEmailModal')->with(compact('emailArray'))->render();
        return response()->json(['viewEmail' => $viewEmail]);
    }

    public function create(Request $request) { //passing param for custom function
        $companyId = $request->id;
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $occupationList = array(null => __('label.SELECT_OCCUPATION_OPT')) + Occupation::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        $designationList = array(null => __('label.SELECT_DESIGNATION_OPT')) + Designation::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        $specialityList = array('0' => __('label.SELECT_SPECIALITY_OPT')) + Speciality::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        $weaknessList = array('0' => __('label.SELECT_WEAKNESS_OPT')) + weakness::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        $companyInfo = Company::where('id',$companyId)->first();
//        echo '<pre>';        print_r($occupationList);exit;
        return view('contact.create')->with(compact('qpArr', 'occupationList', 'designationList', 'specialityList', 'weaknessList','companyId','companyInfo'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('company/'.$request->company_id.'/contact/create'. $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Contact;
//        echo "<pre>";print_r($target->image); exit;
        $contactArr = $request->contact_number;
        $contact = implode(',', $contactArr);
        $emailArr = $request->email;
        $email = implode(',', $emailArr);

        $target->first_name = $request->first_name;
        $target->last_name = $request->last_name;
        $target->occupation_id = $request->occupation_id;
        $target->company_id = $request->company_id;
        $target->designation_id = $request->designation_id;
        $target->contact_number = $contact;
        $target->email = $email;
        if ($files = $request->file('image')) {
            $imagePath = 'public/image/';
            $imageName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
            $files->move($imagePath, $imageName);
            $target->image = $imageName;
        }
        $target->status = $request->status;
        if ($target->save()) {
            Session::flash('success', __('label.CONTACT_CREATED_SUCCESSFULLY'));
            return redirect('company/'.$request->company_id.'/contact');
        } else {
            Session::flash('error', __('label.CONTACT_COULD_NOT_BE_CREATED'));
            return redirect('company/'.$request->company_id.'/contact/create'. $pageNumber);
        }
    }

    public function details(Request $request) {
        $contactDetails = Contact::Join('occupation', 'contact.occupation_id', '=', 'occupation.id')
                ->Join('company', 'contact.company_id', '=', 'company.id')
                ->Join('designation', 'contact.designation_id', '=', 'designation.id')
                ->select('contact.id', 'contact.first_name', 'contact.last_name', 'contact.contact_number', 'contact.email', 'contact.image', 'contact.status', 'contact.speciality', 'contact.weakness', 'occupation.name as occupation_name', 'occupation.name as occupation_name', 'company.name as company_name', 'designation.name as designation_name')
                ->where('contact.id', $request->id)
                ->first();
        $specialityArray = explode(',', $contactDetails->speciality);
        $contactSpecialities = Speciality::whereIn('id', $specialityArray)->get();

        $weaknessArray = explode(',', $contactDetails->weakness);
        $contactWeakness = Weakness::whereIn('id', $weaknessArray)->get();

        $academicQualification = ContactAcademic::where('contact_id', $request->id)->get();
        $professionalSkill = ContactProfessional::where('contact_id', $request->id)->get();
        $currOrganization = ContactProfessional::where(['contact_id' => $request->id, 'current_working' => '0'])->latest()->take(1)->get();
        $experience = $expertise = [];
        foreach ($professionalSkill as $professional) {
            $experience[] = $professional->experience_year;
            $expertise[] = $professional->expertise_area;
        }
        $totalYearExperience = array_sum($experience);
        $expertiseStr = implode(', ', $expertise);

        $meetUpDetails = MeetUp::select('id', 'location', 'date', 'purpose')->where('contact_id', $request->id)->get();
        $specialities = Speciality::pluck('name', 'id')->toArray();
        $remarks = ContactRemarks::where('contact_id', $request->id)->get();
        return view('contact.viewDetails')->with(compact('contactDetails', 'meetUpDetails', 'specialities', 'contactSpecialities', 'contactWeakness', 'academicQualification', 'professionalSkill', 'totalYearExperience', 'expertiseStr', 'currOrganization', 'remarks'));
    }

    //Meet Up Section Start  
    public function openMeetupModal(Request $request) {
        $createMeetupModal = view('contact.meetup.createMeetup')->with(['contact_id' => $request->contact_id])->render();
        return response()->json(['createMeetupModal' => $createMeetupModal]);
    }

    public function storeMeetup(Request $request) {
        $rules = [
            'location' => ['required'],
            'date' => ['required'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $meetUp = new MeetUp;
        $meetUp->location = $request->location;
        $meetUp->date = $request->date;
        $meetUp->purpose = $request->purpose;
        $meetUp->contact_id = $request->contact_id;

        if ($meetUp->save()) {
            return response()->json(['success']);
        }
    }

    public function editMeetup(Request $request) {
        $meetUp = MeetUp::findOrFail($request->meetup_id);
        $editMeetUpData = view('contact.meetup.editMeetup')->with(['meetUp' => $meetUp])->render();
        return response()->json(['editMeetUpData' => $editMeetUpData]);
    }

    public function updateMeetup(Request $request) {
        $rules = [
            'location' => ['required'],
            'date' => ['required'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $meetUp = MeetUp::findOrFail($request->meetup_id);
        $meetUp->location = $request->location;
        $meetUp->date = $request->date;
        $meetUp->purpose = $request->purpose;
        if ($meetUp->save()) {
            return response()->json(['success']);
        }
    }

    public function deleteMeetup(Request $request) {
        $deleteMeetUp = MeetUp::findOrFail($request->id)->delete();
        if ($deleteMeetUp) {
            Session::flash('success', __('label.MEET_UP_HAS_BEEN_DELETED_SUCCESSFULLY'));
            return redirect()->back();
        }
    }

    //End Meet up
    //Specialty Section Start  
    public function openSpecialtyModal(Request $request) {
        $specialities = Speciality::orderBy('order', 'asc')->get();
        $createSpecialtyModal = view('contact.specialty.createSpecialty')->with(['contact_id' => $request->contact_id, 'specialities' => $specialities])->render();
        return response()->json(['createSpecialtyModal' => $createSpecialtyModal]);
    }

    public function storeSpecialty(Request $request) {
        if (!empty($request->speciality)) {
            $specilityArray = $request->speciality;
            $specilityString = implode(',', $specilityArray);
        } else {
            $specilityString = "";
        }

        $addSpecility = Contact::findOrFail($request->contact_id);
        $addSpecility->speciality = $specilityString;
        if ($addSpecility->save()) {
            return response()->json(['success']);
        }
    }

    public function editSpecialty(Request $request) {
        $specialities = Speciality::orderBy('order', 'asc')->get();
        $contactSpecialities = Contact::where('id', $request->contact_id)->select('speciality')->first();
        $contactSpecialityArray = explode(',', $contactSpecialities->speciality);
        $contactSpecialityArr = array_flip($contactSpecialityArray);
        $editSpecialtyData = view('contact.specialty.editSpecialty')->with(['contact_id' => $request->contact_id, 'specialities' => $specialities, 'contactSpecialityArr' => $contactSpecialityArr])->render();
        return response()->json(['editSpecialtyData' => $editSpecialtyData]);
    }

    public function updateSpecialty(Request $request) {
        if (!empty($request->speciality)) {
            $specilityArray = $request->speciality;
            $specilityString = implode(',', $specilityArray);
        } else {
            $specilityString = "";
        }
        $specialty = Contact::findOrFail($request->contact_id);
        $specialty->speciality = $specilityString;
        if ($specialty->save()) {
            return response()->json(['success']);
        }
    }

    //End Specialty
    //Weekness Section Start  
    public function openWeaknessModal(Request $request) {
        $allWeakness = Weakness::orderBy('order', 'asc')->get();
        $createWeaknessModal = view('contact.weakness.createWeakness')->with(['contact_id' => $request->contact_id, 'allWeakness' => $allWeakness])->render();
        return response()->json(['createWeaknessModal' => $createWeaknessModal]);
    }

    public function storeWeekness(Request $request) {
        if (!empty($request->weakness)) {
            $weaknessArray = $request->weakness;
            $weaknessString = implode(',', $weaknessArray);
        } else {
            $weaknessString = "";
        }

        $addWeakness = Contact::findOrFail($request->contact_id);
        $addWeakness->weakness = $weaknessString;
        if ($addWeakness->save()) {
            return response()->json(['success']);
        }
    }

    public function editWeakness(Request $request) {
        $allWeakness = Weakness::orderBy('order', 'asc')->get();
        $contactWeakness = Contact::where('id', $request->contact_id)->select('weakness')->first();
        $contactWeaknessArray = explode(',', $contactWeakness->weakness);
        $editWeaknessData = view('contact.weakness.editWeakness')->with(['contact_id' => $request->contact_id, 'allWeakness' => $allWeakness, 'contactWeaknessArray' => $contactWeaknessArray])->render();
        return response()->json(['editWeaknessData' => $editWeaknessData]);
    }

    public function updateWeakness(Request $request) {
        if (!empty($request->weakness)) {
            $weaknessArray = $request->weakness;
            $weaknessString = implode(',', $weaknessArray);
        } else {
            $weaknessString = "";
        }
        $updateWeakness = Contact::findOrFail($request->contact_id);
        $updateWeakness->weakness = $weaknessString;
        if ($updateWeakness->save()) {
            return response()->json(['success']);
        }
    }

    //End Weekness
    //Academic Section Start  
    public function openAcademicModal(Request $request) {
        $createAcademicModal = view('contact.academic.createAcademic')->with(['contact_id' => $request->contact_id])->render();
        return response()->json(['createAcademicModal' => $createAcademicModal]);
    }

    public function storeAcademic(Request $request) {
        $storeAcademic = new ContactAcademic;
        $storeAcademic->degree_name = !empty($request->degree_name) ? $request->degree_name : '';
        $storeAcademic->institute = !empty($request->institute) ? $request->institute : '';
        $storeAcademic->batch = !empty($request->batch) ? $request->batch : '';
        $storeAcademic->remarks = !empty($request->remarks) ? $request->remarks : '';
        $storeAcademic->contact_id = $request->contact_id;

        if ($storeAcademic->save()) {
            return response()->json(['success']);
        }
    }

    public function editAcademic(Request $request) {
        $editData = ContactAcademic::findOrFail($request->academic_id);
        $editAcademicData = view('contact.academic.editAcademic')->with(['editData' => $editData])->render();
        return response()->json(['editAcademicData' => $editAcademicData]);
    }

    public function updateAcademic(Request $request) {
        $updateAcademic = ContactAcademic::findOrFail($request->id);
        $updateAcademic->degree_name = !empty($request->degree_name) ? $request->degree_name : '';
        $updateAcademic->institute = !empty($request->institute) ? $request->institute : '';
        $updateAcademic->batch = !empty($request->batch) ? $request->batch : '';
        $updateAcademic->remarks = !empty($request->remarks) ? $request->remarks : '';
        if ($updateAcademic->save()) {
            return response()->json(['success']);
        }
    }

    public function deleteAcademic(Request $request) {
        $deleteAcademic = ContactAcademic::findOrFail($request->id)->delete();
        if ($deleteAcademic) {
            Session::flash('success', __('label.ACADEMIC_DELETED_SUCCESSFULLY'));
            return redirect()->back();
        }
    }

    //End Academic section
    //Professional Section Start  
    public function openProfessionalModal(Request $request) {
        $createProfessionalModal = view('contact.professional.createProfessional')->with(['contact_id' => $request->contact_id])->render();
        return response()->json(['createProfessionalModal' => $createProfessionalModal]);
    }

    public function storeProfessional(Request $request) {
        $storeProfessional = new ContactProfessional;
        $storeProfessional->organization_name = !empty($request->organization_name) ? $request->organization_name : '';
        $storeProfessional->experience_year = !empty($request->experience_year) ? $request->experience_year : '';
        $storeProfessional->expertise_area = !empty($request->expertise_area) ? $request->expertise_area : '';
        $storeProfessional->current_working = !empty($request->current_working) ? $request->current_working : '0';
        $storeProfessional->contact_id = $request->contact_id;
        if ($storeProfessional->save()) {
            return response()->json(['success']);
        }
    }

    public function editProfessional(Request $request) {
        $editData = ContactProfessional::findOrFail($request->professional_id);
//        Helper::dump($editData->toArray());
        $editProfessionalData = view('contact.professional.editProfessional')->with(['editData' => $editData])->render();
        return response()->json(['editProfessionalData' => $editProfessionalData]);
    }

    public function updateProfessional(Request $request) {
        $updateProfessional = ContactProfessional::findOrFail($request->id);
        $updateProfessional->organization_name = !empty($request->organization_name) ? $request->organization_name : '';
        $updateProfessional->experience_year = !empty($request->experience_year) ? $request->experience_year : '';
        $updateProfessional->expertise_area = !empty($request->expertise_area) ? $request->expertise_area : '';
        $updateProfessional->current_working = !empty($request->current_working) ? $request->current_working : '0';
        if ($updateProfessional->save()) {
            return response()->json(['success']);
        }
    }

    public function deleteProfessional(Request $request) {
        $deleteProfessional = ContactProfessional::findOrFail($request->id);
        if ($deleteProfessional->delete()) {
            Session::flash('success', __('label.PROFESSIONAL_DELETED_SUCCESSFULLY'));
            return redirect()->back();
        }
    }

    //End Professional section
    //Remarks Section Start  
    public function openRemarksModal(Request $request) {
        $createRemarksModal = view('contact.remarks.createRemarks')->with(['contact_id' => $request->contact_id])->render();
        return response()->json(['createRemarksModal' => $createRemarksModal]);
    }

    public function storeRemarks(Request $request) {
        $storeRemarks = new ContactRemarks;
        $storeRemarks->date = !empty($request->date) ? $request->date : '';
        $storeRemarks->remarks = !empty($request->remarks) ? $request->remarks : '';
        $storeRemarks->contact_id = $request->contact_id;
        if ($storeRemarks->save()) {
            return response()->json(['success']);
        }
    }

    public function editRemarks(Request $request) {
        $editData = ContactRemarks::findOrFail($request->remarks_id);
        $editRemarksData = view('contact.remarks.editRemarks')->with(['editData' => $editData])->render();
        return response()->json(['editRemarksData' => $editRemarksData]);
    }

    public function updateRemarks(Request $request) {
        $updateRemarks = ContactRemarks::findOrFail($request->id);
        $updateRemarks->date = !empty($request->date) ? $request->date : '';
        $updateRemarks->remarks = !empty($request->remarks) ? $request->remarks : '';
        if ($updateRemarks->save()) {
            return response()->json(['success']);
        }
    }

    public function deleteRemarks(Request $request) {
        $deleteRemarks = ContactRemarks::findOrFail($request->id);
        if ($deleteRemarks->delete()) {
            Session::flash('success', __('label.REMARKS_DELETED_SUCCESSFULLY'));
            return redirect()->back();
        }
    }

    //End Remarks section

    public function edit(Request $request, $id) {
        $target = Contact::findOrFail($id);
        $qpArr = $request->all();
        $occupationList = array('0' => __('label.SELECT_OCCUPATION_OPT')) + Occupation::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        $companyList = array('0' => __('label.SELECT_COMPANY_OPT')) + Company::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        $designationList = array('0' => __('label.SELECT_DESIGNATION_OPT')) + Designation::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        $specialityList = array('0' => __('label.SELECT_SPECIALITY_OPT')) + Speciality::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        $weaknessList = array('0' => __('label.SELECT_WEAKNESS_OPT')) + weakness::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();

        $contactNoArr = explode(',', $target->contact_number);
        $emailArr = explode(',', $target->email);

        return view('contact.edit')->with(compact('target', 'qpArr', 'occupationList', 'companyList', 'designationList', 'specialityList', 'weaknessList', 'contactNoArr', 'emailArr'));
    }

    public function update(Request $request, $id) { //
        $target = Contact::find($id);

        //begin back same page after update
        $qpArr = $request->all();

        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('contact/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $contactNumber = implode(',', $request->contact_number);
        $email = implode(',', $request->email);

        $target->first_name = $request->first_name;
        $target->last_name = $request->last_name;
        $target->occupation_id = $request->occupation_id;
        $target->company_id = $request->company_id;
        $target->designation_id = $request->designation_id;
        $target->contact_number = $contactNumber;
        $target->email = $email;

        if ($files = $request->file('image')) {
            $imagePath = 'public/image/';
            $imageName = uniqid() . "." . date('Ymd') . "." . $files->getClientOriginalExtension();
            $files->move($imagePath, $imageName);
            $target->image = $imageName;
        }
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.CONTACT_UPDATED_SUCCESSFULLY'));
            return redirect('company/'.$target->company_id.'/contact' . $pageNumber);
        } else {
            Session::flash('error', __('label.CONTACT_COULD_NOT_BE_UPDATED'));
            return redirect('contact/'. $id .'/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request) {
        $target = Contact::find($request->id);
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }
        
        if ($target->delete()) {
            Session::flash('error', __('label.CONTACT_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.CONTACT_COULD_NOT_BE_DELETED'));
        }
        return redirect('company/'.$request->companyId.'/contact'. $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'search=' . $request->name . '&status=' . $request->status;
        return Redirect::to('company/'.$request->companyId.'/contact?' . $url);
    }

}

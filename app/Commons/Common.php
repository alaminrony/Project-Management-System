<?php

use Illuminate\Support\Facades\DB;
use App\DailyProduct;
use App\ProductConsumptionDetails;
use App\LotWiseConsumptionDetails;
use App\DailyProductDetails;
use Illuminate\Support\Facades\Auth;
use App\AclUserGroupToAccess;
use App\Product;
use App\Recipe;
use App\RecipeToProcess;
use App\RecipeToProduct;
use App\WashType;
use App\WashTypeToProcess;
use App\User;
use App\BatchCard;
use App\ProductToSupplier;
use App\ProductConsumptionMaster;
use App\ProductToManufacturer;
use App\ProductCheckInMaster;
use App\Factory;
use App\ProductToProcess;
use App\DryerMachine;
use App\DryerType;
use App\ProductCheckInDetails;
use App\Department;
use App\Process;
use App\MfAddressBook;
use App\Configuration;
use Illuminate\Http\Request;

class Common {

//    private $viewStatusArr = [0 => 'Pending for Approval', 1 => 'Approved'];
//    private $statusArr = [0 => ['status' => 'Pending for Approval', 'label' => 'warning']
//        , 1 => ['status' => 'Approved', 'label' => 'success']];

    public static function userAccess() {
        //ACL ACCESS LIST
        $accessGroupArr = AclUserGroupToAccess::where('group_id', Auth::user()->group_id)
                        ->select('*')->get();

        $userAccessArr = [];
        if (!$accessGroupArr->isEmpty()) {
            foreach ($accessGroupArr as $item) {
                $userAccessArr[$item->module_id][$item->access_id] = $item->access_id;
            }
        }
        //ENDOF ACL ACCESS LIST
        return $userAccessArr;
    }

    public static function groupHasRoleAccess($groupId) {
        $accessGroupArr = AclUserGroupToAccess::where('group_id', $groupId)
                        ->select('*')->get();
        if ($groupId != 1 && $accessGroupArr->isEmpty()) {
            return 1;
        }else{
            return 0;
        }
    }

    public static function getSupplierManufacturer(Request $request, $loadView) {
        //product wise supplier

        $supplierArr = ['0' => __('label.SELECT_SUPPLIER_OPT')] + ProductToSupplier::join('supplier', 'supplier.id', '=', 'product_to_supplier.supplier_id')
                        ->where('product_id', $request->product_id)->pluck('name', 'supplier.id')->toArray();

        //product wise manufacturer
        $manufacturerArr = ['0' => __('label.SELECT_MANUFACTURER_OPT')] + ProductToManufacturer::join('manufacturer', 'manufacturer.id', '=', 'product_to_manufacturer.manufacturer_id')
                        ->where('product_id', $request->product_id)->pluck('name', 'manufacturer.id')->toArray();

        $view = view($loadView, compact('supplierArr', 'manufacturerArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getManufacturerAddress(Request $request) {
        //manufacturer wise address
        $addressArr = ['0' => __('label.SELECT_ADDRESS_OPT')] + MfAddressBook::join('country', 'country.id', '=', 'manufacturer_adressbook.country_id')
                        ->where('manufacturer_adressbook.manufacturer_id', $request->manufacturer_id)
                        ->select('manufacturer_adressbook.id', DB::raw("CONCAT(manufacturer_adressbook.title,' (',country.name,') ') as title"))
                        ->pluck('title', 'manufacturer_adressbook.id')->toArray();

        $view = view('productCheckIn.showManufacturerAddress', compact('addressArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function purchaseNew(Request $request) {
        $productInfo = Product::join('measure_unit', 'measure_unit.id', '=', 'product.primary_unit_id')->select('product.name as pname'
                                , 'product.available_quantity'
                                , 'measure_unit.name as unit_name')
                        ->where('product.id', $request->product_id)->first();

        //Quantity check for Source:: Adjustment and Substore
        if (($request->type == '1') || ($request->type == '3')) {
            if ($request->quantity > $productInfo->available_quantity) {
                return Response::json(['success' => false, 'heading' => 'Error', 'message' => __('label.RQUIRED_QUANTITY_EXCEEDS_FOR') . $productInfo->pname], 401);
            }
        }

        return response()->json(['productName' => $productInfo->pname, 'productUnit' => $productInfo->unit_name]);
    }

    public static function productHints(Request $request) {
        $unit = Product::join('measure_unit', 'measure_unit.id', '=', 'product.primary_unit_id')
                        ->select('measure_unit.name as unit_name')->where('product.id', $request->product_id)->first();

        $quantity = Product::where('id', $request->product_id)
                        ->select('available_quantity')->first();

        return response()->json(['quantity' => $quantity->available_quantity, 'unit_name' => $unit->unit_name]);
    }

    public static function getProductConsumptionDetails(Request $request) {
        $userArr = User::where('status', 1)->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'id')
                        ->pluck('name', 'id')->toArray();

        // get Adjustment Primary Data
        $adjustmentInfo = ProductConsumptionMaster::select('pro_consumption_master.*')
                        ->where('pro_consumption_master.id', $request->adjustment_id)->first();

        $adjustmentDetailsArr = ProductConsumptionDetails::where('pro_consumption_details.master_id', '=', $request->adjustment_id)
                        ->join('product', 'product.id', '=', 'pro_consumption_details.product_id')
                        ->join('product_category', 'product_category.id', '=', 'product.product_category_id')
                        ->select('product.name as product_name', 'product_category.name as category_name', 'pro_consumption_details.*'
                        )->get();

        $lotInfoArr = ProductConsumptionDetails::join('pro_consumption_lot_wise_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                        ->where('pro_consumption_details.master_id', '=', $request->adjustment_id)
                        ->select('pro_consumption_details.id as details_id', 'pro_consumption_details.product_id', 'pro_consumption_lot_wise_details.lot_number', 'pro_consumption_lot_wise_details.quantity'
                                , 'pro_consumption_lot_wise_details.rate', 'pro_consumption_lot_wise_details.amount')->get();

        //Fetch Lot Information and form Node: Start
        $productWithLotArr = [];
        if (!$lotInfoArr->isEmpty()) {
            $i = 0;
            foreach ($lotInfoArr as $target) {
                $productWithLotArr[$target->product_id][$target->details_id][$i]['lot_number'] = $target->lot_number;
                $productWithLotArr[$target->product_id][$target->details_id][$i]['quantity'] = $target->quantity;
                $productWithLotArr[$target->product_id][$target->details_id][$i]['rate'] = $target->rate;
                $productWithLotArr[$target->product_id][$target->details_id][$i]['amount'] = $target->amount;
                $i++;
            }//foreach           
        }
        //Fetch Lot Information and form Node: End
        $statusArr = [0 => 'Pending for Approval', 1 => 'Approved'];

        $view = view('productConsumption.productDetails', compact('adjustmentDetailsArr', 'adjustmentInfo', 'statusArr', 'userArr', 'productWithLotArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function activateOrDeactive(Request $request, $statusInfo, $activationMessage) {
        $rules = [
            'cause' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }
        $target = Recipe::select('act_deact_cause')->where('id', $request->id)->first();
        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
            return redirect('recipe');
        }
        $returnMessage = '';
        $arr = (array) json_decode($target->act_deact_cause);
        //prepare json string
        $newNode[] = array('action' => $request->type,
            'date' => date('Y-m-d H:i:s'),
            'by' => Auth::user()->id,
            'cause' => $request->cause);
        $infoNewArr = array_merge($arr, $newNode);

        $info['status'] = $statusInfo;
        $returnMessage = $activationMessage;

        $info['act_deact_cause'] = json_encode($infoNewArr);
        Recipe::where('id', $request->id)->update($info);
        return Response::json(['success' => true, 'heading' => 'Success', 'message' => $returnMessage], 200);
    }

    public static function getRecipeDetails(Request $request, $id = null, $module_id, $detailsView) {
        $target = Recipe::join('style', 'style.id', '=', 'recipe.style_id')
                ->join('garments_type', 'garments_type.id', '=', 'recipe.garments_type_id')
                ->join('factory', 'factory.id', '=', 'recipe.factory_id')
                ->join('buyer', 'buyer.id', '=', 'recipe.buyer_id')
                ->join('machine_model', 'machine_model.id', '=', 'recipe.machine_model_id')
                ->join('shade', 'shade.id', '=', 'recipe.shade_id')
                ->join('season', 'season.id', '=', 'recipe.season_id')
                ->join('color', 'color.id', '=', 'recipe.color_id')
                ->leftJoin('dryer_type', 'dryer_type.id', '=', 'recipe.dryer_type_id')
                ->leftJoin('wash', 'wash.id', '=', 'recipe.wash_id')
                ->select('recipe.*', 'style.name as style', 'garments_type.name as garments_type', 'factory.name as factory'
                , 'buyer.name as buyer', 'buyer.logo as buyer_logo', 'machine_model.name as machine_model'
                , 'machine_model.rpm', 'wash.name as wash', 'dryer_type.name as dryer_type_name'
                , 'shade.name as shade_name', 'season.name as season'
                , 'color.name as color');
        if (!empty($id)) {
            $target = $target->where('recipe.id', $id);
        } else {
            $target = $target->where('recipe.id', $request->recipe_id);
        }
        $target = $target->first();


        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('recipe');
        }


        $processArr = RecipeToProcess::join('process', 'process.id', '=', 'recipe_to_process.process_id')
                ->orderBy('recipe_to_process.id', 'asc');
        if (!empty($id)) {
            $processArr = $processArr->where('recipe_id', $id);
        } else {
            $processArr = $processArr->where('recipe_id', $request->recipe_id);
        }


        $processArr = $processArr->select('recipe_to_process.*', 'process.name', 'process.water as water_type'
                        , 'process.process_type_id', 'process.id as process_id')->get();


        $productArrPre = RecipeToProduct::join('recipe_to_process', 'recipe_to_process.id', '=', 'recipe_to_product.rtp_id')
                ->join('product', 'product.id', '=', 'recipe_to_product.product_id');
        if (!empty($id)) {
            $productArrPre = $productArrPre->where('recipe_to_process.recipe_id', $id);
        } else {
            $productArrPre = $productArrPre->where('recipe_to_process.recipe_id', $request->recipe_id);
        }
        $productArrPre = $productArrPre->select('product.name', 'recipe_to_product.qty', 'recipe_to_product.rtp_id'
                        , 'recipe_to_product.total_qty', 'recipe_to_product.total_qty_detail', 'recipe_to_product.formula')->get();

        //prepare product Array
        $productArr = [];
        foreach ($productArrPre as $item) {
            $productArr[$item->rtp_id][] = $item->toArray();
        }
        $targetArr = [];
        $i = $totalWater = 0;
        foreach ($processArr as $process) {
            $targetArr[$i]['process_id'] = $process['id'];
            $targetArr[$i]['process'] = $process['name'];
            $targetArr[$i]['process_type_id'] = $process['process_type_id'];
            $targetArr[$i]['dry_chemical'] = $process['dry_chemical'];
            $targetArr[$i]['water'] = $process['water'];
            $targetArr[$i]['water_ratio'] = $process['water_ratio'];
            $targetArr[$i]['ph'] = $process['ph'];
            $targetArr[$i]['temperature'] = $process['temperature'];
            $targetArr[$i]['time'] = $process['time'];
            $targetArr[$i]['remarks'] = $process['remarks'];
            $targetArr[$i]['water_type'] = $process['water_type'];
            $totalWater += $process['water'];
            $i++;
        }
        $formulaArr = [1 => ['formula' => 'G/L', 'label' => 'success']
            , 2 => ['formula' => '%', 'label' => 'warning']
            , 3 => ['formula' => 'Direct Amount', 'label' => 'primary']
        ];
        //Prepare ProcessArr for WashType and Added Process for Recipe
        $washTypeArr = WashType::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $washTypeInfoArr = WashTypeToProcess::orderBy('id', 'asc');
        if (!empty($id)) {
            $washTypeInfoArr = $washTypeInfoArr->where('recipe_id', $id);
        } else {
            $washTypeInfoArr = $washTypeInfoArr->where('recipe_id', $request->recipe_id);
        }
        $washTypeInfoArr = $washTypeInfoArr->pluck('process_id', 'wash_type_id')->toArray();

        $processedWashTypeArr = [];
        if (!empty($washTypeInfoArr)) {
            foreach ($washTypeInfoArr as $washTypeId => $process) {
                $processedWashTypeArr[$washTypeId] = (array) json_decode($process);
            }
        }
        $processNameList = Process::pluck('name', 'id')->toArray();
        $washTypeToWaterArr = (array) json_decode($target->wash_type_to_water);

        $userAccessArr = self::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[$module_id][6])) {
                return redirect('dashboard');
            }
            return view('recipe.print')->with(compact('targetArr', 'target', 'productArr', 'totalWater', 'formulaArr'
                                    , 'processedWashTypeArr', 'processNameList', 'washTypeArr', 'washTypeToWaterArr'));
        } else if ($request->view == 'pdf') {
            $pdf = PDF::loadView('recipe.print', compact('targetArr', 'target', 'productArr', 'totalWater', 'formulaArr'
                                    , 'processedWashTypeArr', 'processNameList', 'washTypeArr', 'washTypeToWaterArr'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('recipe.pdf');
        } else {
            $view = view('recipe.' . $detailsView, compact('targetArr', 'target', 'productArr', 'totalWater'
                            , 'formulaArr', 'processedWashTypeArr', 'processNameList', 'washTypeArr', 'washTypeToWaterArr'))->render();
            return response()->json(['html' => $view]);
        }
    }

    public static function loadBatchToken(Request $request, $load) {
        $query = "%" . $request->search_keyword . "%";
        $tokenNumberArr = BatchCard::where('reference_no', 'LIKE', $query)->latest()->take(20)->get(['reference_no', 'id']);

        $view = view($load, compact('tokenNumberArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getFactoryCode(Request $request) {
        $factoryInfo = Factory::find($request->factory_id);
        //$factoryCode = !empty($numberOfFactory->total_factory) ? $factoryInfo->code . '-' .date('Y-m-d H:i:s').'-'.($numberOfFactory->total_factory + 1) : $factoryInfo->code .'-'.date('Y-m-d H:i:s').'-'. 1;
        $factoryCode = $factoryInfo->code . '-' . date('ymd-His');
        return response()->json(['factoryCode' => $factoryCode]);
    }

    public static function getDryerMachine(Request $request) {
        //Get Dryer Type Capacity & Category Information based on dryer type
        $dryerTypeInfo = DryerType::join('dryer_category', 'dryer_category.id', '=', 'dryer_type.dryer_category_id')
                        ->where('dryer_type.id', $request->dryer_type_id)
                        ->select('dryer_type.capacity as dryer_capacity', 'dryer_category.name as dryer_category')->first();
        $dryerMachineArr = ['0' => __('label.SELECT_DRYER_MACHINE_OPT')] + DryerMachine::where('dryer_type_id', $request->dryer_type_id)
                        ->pluck('machine_no', 'id')->toArray();
        $view = view('recipe.showDryerMachine', compact('dryerMachineArr'))->render();
        return response()->json(['html' => $view, 'dryerTypeCapacity' => $dryerTypeInfo->dryer_category . ' & ' . $dryerTypeInfo->dryer_capacity]);
    }

    public static function addProcess(Request $request) {
        $identifier = uniqid();
        $processInfo = Process::find($request->process_id);
        $quantityInGram = $request->wash_lot_quantity_weight * 1000;
        //calculation water 
        $water = $request->wash_lot_quantity_weight * $request->water_ratio;
        //if no product is selected and if this process is not water
        if ($processInfo->process_type_id == '1' && $processInfo->water != '1' && empty($request->product_id)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.NO_PRODUCT_IS_SELECTED')), 401);
        }


        if (!empty($request->product_id)) {
            $productArr = Product::whereIn('id', $request->product_id)
                            ->select('name', 'id', 'type_of_dosage_ratio', 'from_dosage', 'to_dosage')->get();
        }

        $html = view('recipe.recipeDetails', compact('identifier', 'processInfo', 'quantityInGram'
                        , 'productArr', 'water'))->render();
        return response()->json(['html' => $html, 'identifier' => $identifier]);
    }

    public static function updateProcess(Request $request) {
        $identifier = uniqid();
        $processInfo = Process::find($request->new_process_id);
        $newProcessId = $request->new_process_id;
        $prevIdentifier = $request->identifier;
        $serialNo = $request->serial_no;
        $ph = $request->selected_ph;
        $temperature = $request->selected_temperature;
        $time = $request->selected_time;
        $dryChemical = $request->selected_dry_chemical;
        $remarks = $request->selected_remarks;
        $selectedProducArr = $request->selected_product;
        $formulaArr = $request->selected_formula;
        $qtyArr = $request->selected_qty;
        $totalQtyArr = $request->selected_total_qty;
        $totalQtyDetailArr = $request->selected_total_qty_detail;
        $tooltips = '';

        if ($processInfo->process_type_id == '1' && $processInfo->water != '1' && empty($request->product_id)) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => __('label.NO_PRODUCT_IS_SELECTED')), 401);
        }
        if (!empty($request->product_id)) {
            $productArr = Product::whereIn('id', $request->product_id)->select('name', 'id', 'type_of_dosage_ratio', 'from_dosage', 'to_dosage')->get();
        }

        $recipeInfo = [];
        if (!empty($selectedProducArr)) {
            $i = 0;
            foreach ($selectedProducArr as $index => $productId) {
                $recipeInfo['formula'][$identifier][$processInfo->id][$productId] = isset($formulaArr[$index]['productId']) && $formulaArr[$index]['productId'] == $productId ? $formulaArr[$index]['formula'] : '';
                $recipeInfo['qty'][$identifier][$processInfo->id][$productId] = isset($qtyArr[$index]['productId']) && $qtyArr[$index]['productId'] == $productId ? $qtyArr[$index]['qty'] : '';
                $recipeInfo['total_qty'][$identifier][$processInfo->id][$productId] = isset($totalQtyArr[$index]['productId']) && $totalQtyArr[$index]['productId'] == $productId ? $totalQtyArr[$index]['totalQty'] : '';
                $recipeInfo['total_qty_detail'][$identifier][$processInfo->id][$productId] = isset($totalQtyDetailArr[$index]['productId']) && $totalQtyDetailArr[$index]['productId'] == $productId ? $totalQtyDetailArr[$index]['totalQtyDetail'] : '';
                $i++;
            }
        }
        //calculation water 
        $water = $request->wash_lot_quantity_weight * $request->water_ratio;
        $html = view('recipe.recipeDetails', compact('identifier', 'processInfo', 'quantityInGram'
                        , 'tooltips', 'productArr', 'water', 'serialNo', 'ph', 'temperature', 'time'
                        , 'dryChemical', 'remarks', 'recipeInfo', 'prevIdentifier'))->render();
        return response()->json(['html' => $html, 'selectedOrder' => $serialNo, 'newIdentifier' => $identifier
                    , 'newProcessId' => $newProcessId]);
    }

    public static function getProcessWiseProduct(Request $request) {
        $productList = ProductToProcess::join('product', 'product.id', '=', 'product_to_process.product_id')
                        ->where('product_to_process.process_id', $request->process_id)->pluck('product.name', 'product.id')->toArray();
        $producIdArr = [];
        if (!empty($request->product_id)) {
            $producIdArr = array_filter($request->product_id);
        }
        $waterRatio = $request->water_ratio;
        $processInfo = Process::find($request->process_id);
        $view = view('recipe.showProduct', compact('productList', 'producIdArr', 'waterRatio'))->render();
        return response()->json(['html' => $view, 'processInfo' => $processInfo]);
    }

    public static function getProductDetails(Request $request, $demandIdForPrint = null, $loadView, $moduleId) {
        $userArr = User::where('status', 1)->select(DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'id')
                        ->pluck('name', 'id')->toArray();

        // get Substore Demand Primary Data
        $demandInfo = ProductConsumptionMaster::select('pro_consumption_master.*');
        if (!empty($demandIdForPrint)) {//For Printing Purpose
            $demandInfo = $demandInfo->where('pro_consumption_master.id', $demandIdForPrint);
        } else {//For Detail Pop-Up
            $demandInfo = $demandInfo->where('pro_consumption_master.id', $request->demand_id);
        }
        $demandInfo = $demandInfo->first();

        // get Substore Demand Product Details Data
        $demandDetailsArr = ProductConsumptionDetails::join('pro_consumption_master', 'pro_consumption_master.id', '=', 'pro_consumption_details.master_id')
                ->join('product', 'product.id', '=', 'pro_consumption_details.product_id')
                ->join('product_category', 'product_category.id', '=', 'product.product_category_id');
        if (!empty($demandIdForPrint)) {//For Printing Purpose
            $demandDetailsArr = $demandDetailsArr->where('pro_consumption_master.id', $demandIdForPrint);
        } else {//For Detail Pop-Up
            $demandDetailsArr = $demandDetailsArr->where('pro_consumption_master.id', $request->demand_id);
        }
        $demandDetailsArr = $demandDetailsArr->select('product.name as product_name', 'product.id as product_id'
                        , 'product_category.name as category_name'
                        , 'pro_consumption_details.*')
                ->where('pro_consumption_master.status', 1)
                ->where('pro_consumption_master.source', '3')
                ->get();

        //Fetch Lot Information and form Node: Start
        $productWithLotArr = [];
        if (!$demandDetailsArr->isEmpty()) {
            foreach ($demandDetailsArr as $target) {
                $lotInfoArr = ProductConsumptionDetails::join('pro_consumption_lot_wise_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id');
                if (!empty($demandIdForPrint)) {//For Printing Purpose
                    $lotInfoArr = $lotInfoArr->where('pro_consumption_details.master_id', $demandIdForPrint);
                } else {//For Detail Pop-Up
                    $lotInfoArr = $lotInfoArr->where('pro_consumption_details.master_id', $request->demand_id);
                }
                $lotInfoArr = $lotInfoArr->where('pro_consumption_details.product_id', $target->product_id)
                                ->select('pro_consumption_lot_wise_details.lot_number', 'pro_consumption_lot_wise_details.quantity'
                                        , 'pro_consumption_lot_wise_details.rate', 'pro_consumption_lot_wise_details.amount')->get();

                $productWithLotArr[$target->product_id] = $lotInfoArr->toArray();
            }//foreach           
        }
        //Fetch Lot Information and form Node: End
        $userAccessArr = self::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[$moduleId][6])) {
                return redirect('dashboard');
            }
            return view('substoreDemand.print')->with(compact('demandDetailsArr', 'demandInfo', 'productWithLotArr', 'userArr'));
        }
        $view = view('substoreDemand.' . $loadView, compact('demandDetailsArr', 'demandInfo', 'productWithLotArr'
                        , 'userArr'))->render();
        return response()->json(['html' => $view]);
    }

    public static function showHistory(Request $request) {
        $recipeInfo = Recipe::select('reference_no', 'act_deact_cause')->where('id', $request->recipe_id)->first();
        $actDeactHistoryArrTemp = json_decode($recipeInfo->act_deact_cause);
        $actDeactHistoryArr = [];
        if (!empty($actDeactHistoryArrTemp)) {
            foreach ($actDeactHistoryArrTemp as $node) {
                $actDeactHistoryArr[$node->date] = $node;
            }
        }
        krsort($actDeactHistoryArr);
        //user list Arr
        $userArr = User::select('users.first_name', 'users.last_name', 'users.username', 'users.id')->get();
        $userList = [];
        foreach ($userArr as $user) {
            if (!empty($user->first_name) && !empty($user->last_name)) {
                $userList[$user->id] = $user->first_name . ' ' . $user->last_name;
            } else {
                $userList[$user->id] = $user->username;
            }
        }
        //user list
        $view = view('recipe.detailsHistory', compact('recipeInfo', 'actDeactHistoryArr', 'userList'))->render();
        return response()->json(['html' => $view]);
    }
    
    public static function loadProductName(Request $request) {
        $query = "%" . $request->product_name . "%";
        $nameArr = Product::where('name', 'LIKE', $query)->get(['name']);

        $view = view('product.showProductName', compact('nameArr'))->render();
        return response()->json(['html' => $view]);
    }
}

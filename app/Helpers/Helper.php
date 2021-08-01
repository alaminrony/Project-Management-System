<?php

use Illuminate\Support\Facades\DB;
use App\DailyProduct;
use App\ProductCheckInDetails;
use App\ProductConsumptionDetails;
use App\LotWiseConsumptionDetails;
use App\DailyProductDetails;
use App\Product;
use Illuminate\Support\Facades\Auth;
use App\Configuration;
use App\AclUserGroupToAccess;

class Helper {
    //function for back same page after update,delete,cancel
    public static function queryPageStr($qpArr) {
        //link for same page after query
        $qpStr = '';
        if (!empty($qpArr)){
            $qpStr .= '?';
            foreach ($qpArr as $key => $value) {
                if ($value != '') {
                    $qpStr .= $key . '=' . $value . '&';
                }
            }
            $qpStr = trim($qpStr, '&');
            return $qpStr;
        }
    }
     public static function dump($data){
        echo "<pre>";print_r($data);exit;
    }
    
    public static function printDate($date = '0000-00-00') {
        return date('j F, Y', strtotime($date));
    }

    public static function printDateFormat($date = '0000-00-00') {
        return date('d F Y \a\t g:i a', strtotime($date));
    }
    
    public static function formatDate($date = '0000-00-00') {
        if(!empty($date)){
            return date('d F Y \a\t g:i a', strtotime($date));
        }else{
            return '';
        }
        
    }
    
    
    public static function getEventTypeArr() {
        $eventTypeArr = ['1' => __('label.EVENT'), '2' => __('label.CONSIDERATION')];
        return $eventTypeArr;
    }

    // public static function getMonthArr() {
    // $eventTypeArr = ['1' => __('label.EVENT'), '2' => __('label.CONSIDERATION')];
    // return $eventTypeArr
    // }
//function for getOrderList
    public static function getOrderList($model = null, $operation = null, $parentId = null, $parentName = null) {

        /*
         * Operation :: 1 = Create, 2= Edit
         */
        $namespacedModel = '\\App\\' . $model;
        $targetArr = $namespacedModel::select(array(DB::raw('COUNT(id) as total')));
        if (!empty($parentId)) {
            $targetArr = $targetArr->where($parentName, $parentId);
        }
        $targetArr = $targetArr->first();
        $count = $targetArr->total;

        //in case of Create, always Increment the number of element in order 
        //to accomodate new Data
        if ($operation == '1') {
            $count++;
        }
        return array_combine(range(1, $count), range(1, $count));
    }

    //function for Insert order
    public static function insertOrder($model = null, $order = null, $id = null, $parentId = null, $parentName = null) {
        $namespacedModel = '\\App\\' . $model;
        $namespacedModel::where('id', $id)->update(['order' => $order]);
        $target = $namespacedModel::where('id', '!=', $id)->where('order', '>=', $order);
        if (!empty($parentId)) {
            $target = $target->where($parentName, $parentId);
        }
        $target = $target->update(['order' => DB::raw('`order`+ 1')]);
    }

    // function for Update Order
    public static function updateOrder($model = null, $newOrder = null, $id = null, $presentOrder = null, $parentId = null, $parentName = null) {
        $namespacedModel = '\\App\\' . $model;
        $namespacedModel::where('id', $id)->update(['order' => $newOrder]);

        //condition for order range
        $target = $namespacedModel::where('id', '!=', $id);
        if (!empty($parentId)) {
            $target = $target->where($parentName, $parentId);
        }

        if ($presentOrder < $newOrder) {
            //$namespacedModel::where('id', '!=', $id)->where('order', '>=', $presentOrder)->where('order', '<=', $newOrder)->update(['order' => DB::raw('`order`- 1')]);
            $target = $target->where('order', '>=', $presentOrder)->where('order', '<=', $newOrder)->update(['order' => DB::raw('`order`- 1')]);
        } else {
            $target = $target->where('order', '>=', $newOrder)->where('order', '<=', $presentOrder)->update(['order' => DB::raw('`order`+ 1')]);
        }
    }

    public static function deleteOrder($model = null, $order = null, $parentId = null, $parentName = null) {
        $namespacedModel = '\\App\\' . $model;
        $target = $namespacedModel::where('order', '>=', $order);
        if (!empty($parentId)) {
            $target = $target->where($parentName, $parentId);
        }

        $target = $target->update(['order' => DB::raw('`order`- 1')]);
    }

    public static function numberformat($num = 0, $digit = 3) {
        return number_format($num, $digit, '.', ',');
    }

    public static function printDateTime($date = '0000-00-00 00:00:00') {
        return date('d/m/y H:i', strtotime($date));
    }

    public static function printOnlyDate($date = '0000-00-00') {
        return date('d/m/y', strtotime($date));
    }

    public static function consumeQuantity($consumptionDetailId = null, $productId = null, $quantity = null) {
        $lotInfo = ProductCheckInDetails::where('product_id', $productId)->where('consumed', '0')->orderBy('id', 'asc')->first();
        if (!empty($lotInfo)) {
            $remainingQuantity = $quantity - ($lotInfo['remaining_quantity']);
            $updateArr['remaining_quantity'] = ($lotInfo['remaining_quantity'] < $quantity ) ? '0' : ($lotInfo['remaining_quantity'] - $quantity);
            if ($remainingQuantity >= 0) {
                $updateArr['consumed'] = '1';
            }
            ProductCheckInDetails::where('id', $lotInfo['id'])->update($updateArr);
            $lotWiseQuantity = ($quantity <= $lotInfo['remaining_quantity']) ? $quantity : $lotInfo['remaining_quantity'];
            //Insert Data to the Lotwise Consumption Details Table
            $dataDetails = new LotWiseConsumptionDetails;
            $dataDetails->consump_details_id = $consumptionDetailId;
            $dataDetails->lot_number = !empty($lotInfo['lot_number']) ? $lotInfo['lot_number'] : '';
            $dataDetails->quantity = !empty($lotWiseQuantity) ? $lotWiseQuantity : '0';
            $dataDetails->rate = !empty($lotInfo['rate']) ? $lotInfo['rate'] : '0';
            $dataDetails->amount = $lotWiseQuantity * $lotInfo['rate'];
            $dataDetails->save(); //Update LotWise Quantity

            if ($remainingQuantity > 0) {
                self::consumeQuantity($consumptionDetailId, $productId, $remainingQuantity);
            }
            return true;
        } else {
            return false;
        }
    }

    public static function generateDateWiseProduct() {
        $cutOffTime = Configuration::select('check_in_time')->first();
        $currentTime = date('H:i:s');


        $toDate = (strtotime($currentTime) <= strtotime($cutOffTime->check_in_time)) ? date('Y-m-d', strtotime("-1 days")) : date('Y-m-d');
        $prevDate = date('Y-m-d', strtotime('-1 day', strtotime($toDate)));
        $productLocationArr = Product::pluck('storage_location', 'id')->toArray();

        //data taken from product check In detail Table
        $productCheckInDetailArr = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_details.master_id', '=', 'product_checkin_master.id')
                        ->where('product_checkin_master.checkin_date', $toDate)
                        ->select(DB::raw('SUM(product_checkin_details.quantity) as total_qty'), DB::raw('SUM(product_checkin_details.amount) as total_amount'), 'product_checkin_details.product_id')
                        ->groupBy('product_id')->get()->toArray();


        //data taken from product check In detail Table
        $productsRateArr = ProductCheckInDetails::join('product_checkin_master', 'product_checkin_details.master_id', '=', 'product_checkin_master.id')
                        ->select(DB::raw('SUM(product_checkin_details.quantity) as total_qty'), DB::raw('SUM(product_checkin_details.amount) as total_amount'), 'product_checkin_details.product_id')
                        ->groupBy('product_id')->get()->toArray();


        //data taken from product consumption detail Table
        $productConsumptionDetailArr = ProductConsumptionDetails::join('pro_consumption_master', 'pro_consumption_details.master_id', '=', 'pro_consumption_master.id')
                        ->where('pro_consumption_master.adjustment_date', $toDate)
                        ->select(DB::raw('SUM(pro_consumption_details.quantity) as total_consumed_qty'), 'pro_consumption_details.product_id')
                        ->groupBy('product_id')->get()->toArray();

        //data taken from lot wise product consumption detail Table
        $lotWiseConsumptionDetailArr = LotWiseConsumptionDetails::join('pro_consumption_details', 'pro_consumption_details.id', '=', 'pro_consumption_lot_wise_details.consump_details_id')
                        ->join('pro_consumption_master', 'pro_consumption_master.id', 'pro_consumption_details.master_id')
                        ->where('pro_consumption_master.adjustment_date', $toDate)
                        ->select(DB::raw('SUM(amount) as total_consumed_amount'), 'pro_consumption_details.product_id')
                        ->groupBy('pro_consumption_details.product_id')->get()->toArray();


        //data taken from  daily product details Table
        $befQtyArr = DailyProductDetails::join('daily_product', 'daily_product.id', '=', 'daily_product_details.master_id')
                        ->where('daily_product.date', $prevDate)->pluck('after_quantity', 'product_id')->toArray();


        //data taken from  daily product details Table
        $befAmountArr = DailyProductDetails::join('daily_product', 'daily_product.id', '=', 'daily_product_details.master_id')
                        ->where('daily_product.date', $prevDate)->pluck('after_amount', 'product_id')->toArray();

        //Preparing Data
        $dataArr = [];

        foreach ($productLocationArr as $productId => $location) {
            $dataArr[$productId]['location'] = $location;
        }

        if (!empty($productCheckInDetailArr)) {
            foreach ($productCheckInDetailArr as $productCheckIn) {
                $dataArr[$productCheckIn['product_id']]['total_qty'] = $productCheckIn['total_qty'];
                $dataArr[$productCheckIn['product_id']]['total_amount'] = $productCheckIn['total_amount'];
            }
        }

        //Find out the rate of products
        if (!empty($productsRateArr)) {
            foreach ($productsRateArr as $productRate) {
                $dataArr[$productRate['product_id']]['rate'] = !empty($productRate['total_qty']) ? ($productRate['total_amount'] / $productRate['total_qty']) : 0;
            }
        }

        if (!empty($productConsumptionDetailArr)) {
            foreach ($productConsumptionDetailArr as $productConsumption) {
                $dataArr[$productConsumption['product_id']]['consumed_qty'] = $productConsumption['total_consumed_qty'];
            }
        }

        if (!empty($lotWiseConsumptionDetailArr)) {
            foreach ($lotWiseConsumptionDetailArr as $lotWiseConsumption) {
                $dataArr[$lotWiseConsumption['product_id']]['consumed_amount'] = $lotWiseConsumption['total_consumed_amount'];
            }
        }

        if (!empty($befQtyArr)) {
            foreach ($befQtyArr as $productId => $afterQuantity) {
                $dataArr[$productId]['prev_qty'] = $afterQuantity;
            }
        }

        if (!empty($befAmountArr)) {
            foreach ($befAmountArr as $productId => $afterAmount) {
                $dataArr[$productId]['prev_amount'] = $afterAmount;
            }
        }

        //get data from product table 
        $productArr = Product::where('status', '1')->where('approval_status', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        $target = new DailyProduct;
        $target->date = $toDate;
        $target->created_at = date('Y-m-d H:i:s');

        $targetArr = [];
        if ($target->save()) {
            foreach ($productArr as $productId => $productName) {
                $targetArr[$productId]['master_id'] = $target->id;
                $targetArr[$productId]['product_id'] = $productId;
                $targetArr[$productId]['location'] = isset($dataArr[$productId]['location']) ? $dataArr[$productId]['location'] : null;
                $targetArr[$productId]['prev_quantity'] = isset($dataArr[$productId]['prev_qty']) ? $dataArr[$productId]['prev_qty'] : 0;
                $targetArr[$productId]['prev_amount'] = isset($dataArr[$productId]['prev_amount']) ? $dataArr[$productId]['prev_amount'] : 0;
                $targetArr[$productId]['checkin_today'] = isset($dataArr[$productId]['total_qty']) ? $dataArr[$productId]['total_qty'] : 0;
                $targetArr[$productId]['checkin_total_amount'] = isset($dataArr[$productId]['total_amount']) ? $dataArr[$productId]['total_amount'] : 0;
                $targetArr[$productId]['checkin_avg_amount'] = isset($dataArr[$productId]['rate']) ? $dataArr[$productId]['rate'] : 0; //isset($dataArr[$productId]['total_amount'], $dataArr[$productId]['total_qty']) ? $dataArr[$productId]['total_amount'] / $dataArr[$productId]['total_qty'] : 0;
                $targetArr[$productId]['consumed_quantity'] = isset($dataArr[$productId]['consumed_qty']) ? $dataArr[$productId]['consumed_qty'] : 0;
                $targetArr[$productId]['consumed_amount'] = isset($dataArr[$productId]['consumed_amount']) ? $dataArr[$productId]['consumed_amount'] : 0;
                $targetArr[$productId]['after_quantity'] = isset($targetArr[$productId]['prev_quantity'], $targetArr[$productId]['checkin_today'], $targetArr[$productId]['consumed_quantity']) ? (($targetArr[$productId]['prev_quantity'] + $targetArr[$productId]['checkin_today']) - $targetArr[$productId]['consumed_quantity']) : 0;
                $targetArr[$productId]['after_amount'] = isset($targetArr[$productId]['prev_amount'], $targetArr[$productId]['checkin_total_amount'], $targetArr[$productId]['consumed_amount']) ? (($targetArr[$productId]['prev_amount'] + $targetArr[$productId]['checkin_total_amount']) - $targetArr[$productId]['consumed_amount']) : 0;
            }
        }
        DailyProductDetails::insert($targetArr);
    }

    //For make Print any data
    public static function pr($data, $number) {
        echo "<pre>";
        print_r($data);
        if ($number == '1') {
            return exit;
        } else {
            return false;
        }
    }

    public static function dateFormat($date = '0000-00-00') {
        return date('d/m/Y', strtotime($date));
    }

    public static function unitConversion($totalQtyStr = "") {
        $pos = strpos($totalQtyStr, ".");
        if ($pos === false) {
            $kgAmnt = $totalQtyStr;
            $gmAmntArr = "";
        } else {
            $totalQtyArr = explode(".", $totalQtyStr);
            $kgAmnt = $totalQtyArr[0];
            $gmAmntArr = $totalQtyArr[1];
        }

        $kgFinalAmntStr = '';
        if ($kgAmnt > 0) {
            $kgFinalAmntStr = (int) $kgAmnt . " " . __('label.UNIT_KG');
        }


        if ($pos !== false) { //If decimal point exists
            $totalAmntStr = str_pad($gmAmntArr, 6, "0", STR_PAD_RIGHT);

            $gmStr = substr($totalAmntStr, 0, 3); //Subtract gram aamount
            $gmFinalAmntStr = "";
            if ($gmStr > 0) {
                $gmFinalAmntStr = (int) $gmStr . " " . __('label.GM');
            }
            $miliGmStr = substr($totalAmntStr, 3, 3); //Subtract miligram aamount
            $mgFinalAmntStr = "";
            if ($miliGmStr > 0) {
                $mgFinalAmntStr = (int) $miliGmStr . " " . __('label.MG');
            }

            $qtyTotalDetail = $kgFinalAmntStr . " " . $gmFinalAmntStr . " " . $mgFinalAmntStr;
        } else {
            $qtyTotalDetail = $kgFinalAmntStr;
        }

        return $qtyTotalDetail;
    }

    public static function getAccessList() {
        //Get User Group Access
        $userGroupToAccessArr = AclUserGroupToAccess::select('acl_user_group_to_access.module_id', 'acl_user_group_to_access.access_id')
                        ->where('acl_user_group_to_access.group_id', '=', Auth::user()->group_id)
                        ->orderBy('acl_user_group_to_access.module_id', 'asc')
                        ->orderBy('acl_user_group_to_access.access_id', 'asc')->get();

        //echo '<pre>';print_r($userGroupToAccessArr->toArray());exit;
        //User_group_Module_to_Access Table
        if (!$userGroupToAccessArr->isEmpty()) {
            foreach ($userGroupToAccessArr as $ma) {
                $moduleToGroupAccessListArr[$ma->module_id][] = $ma->access_id;
            }
        }


        $value = "Hello";
        //session_start();
        //$_SESSION['variableName'] =  $value;
        //echo Session::get('variableName');
        //exit;
        Session::put('moduleToGroupAccessListArr', $moduleToGroupAccessListArr);
        //echo '<pre>';print_r(Session::get('variableName'));
    }

}

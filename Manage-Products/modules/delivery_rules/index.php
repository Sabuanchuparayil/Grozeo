<?php

global $db;
$userid = $_SESSION['admin']->Finascop_UserId;
switch ($op) {
    case 'saveDeliveryRules':

        $data = array_filter($_POST);
        $rdrdata['rdr_deliveryMode'] = $data['rdr_deliveryMode'];
        $rdrdata['rdr_calculationMode'] = $data['rdr_calculationMode'];
        $rdrdata['rdr_fixedRateperkm'] = $data['rdr_fixedRateperkm'];
        $rdrdata['rdr_fixedRateMin'] = $data['rdr_fixedRateMin'];
        $rdrdata['rdr_fixedRateMax'] = $data['rdr_fixedRateMax'];
        $rdrdata['rdr_ruleFor'] = $data['rdr_ruleFor'];
        $rdrdata['rdr_ruleName'] = $data['rdr_ruleName'];
        $rdrdata['rdr_isFreeDelivery'] = $data['rdr_isfreeDeliveryCbx'];
        $rdrdata['rdr_isfreeDeliveryAmt'] = $data['rdr_isfreeDeliveryAmt'];
        $rdrdata['rdr_ruleForId'] = $data['rdr_ruleForId'];
        $rdrdata['rdr_fromkm1'] = $data['rdr_fromkm1'];
        $rdrdata['rdr_tokm1'] = $data['rdr_tokm1'];
        $rdrdata['rdr_amt1'] = $data['rdr_amt1'];
        $rdrdata['rdr_fromkm2'] = $data['rdr_fromkm2'];
        $rdrdata['rdr_tokm2'] = $data['rdr_tokm2'];
        $rdrdata['rdr_amt2'] = $data['rdr_amt2'];
        $rdrdata['rdr_fromkm3'] = $data['rdr_fromkm3'];
        $rdrdata['rdr_tokm3'] = $data['rdr_tokm3'];
        $rdrdata['rdr_amt3'] = $data['rdr_amt3'];

        $rdrdata['rdr_createdOn'] = date('Y-m-d H:i:s');
        $rdrdata['rdr_createdBy'] = $userid;


        $rdrdata = array_filter($rdrdata);
        $db->query('begin');
        $status = $db->perform(FINASCOP_DB . "retaline_delivery_rules", $rdrdata);
        $db->query('commit');
        if ($status) {
            echo "{success: true,msg:'Delivery Charge Saved'}";
        } else {
            echo "{success: false,msg: 'Error occured while saving data' }";
        }
        break;
    case 'storeForGroupStre':
        $type = $_POST['type'];
        switch ($type) {
            case 2:
                $qry = "select store_group_id as id,store_group_name as name from " . FINASCOP_DB . "finascop_branch_group where status = 1 order by store_group_name";
                $data = $db->getMultipleData($qry, true);
                break;
            case 3:
                $qry = "select br_ID as id,br_Name as name from " . FINASCOP_DB . "finascop_branch where br_status = 'Active' order by br_Name";
                $data = $db->getMultipleData($qry, true);
                break;
        }
        if (!empty($data)) {
            echo json_encode($data);
        } else
            echo [];
        break;
    case 'listDeliveryRules':
        $limit = isset($_POST['limit']) ? $_POST['limit'] : 20;
        $start = isset($_POST['start']) ? $_POST['start'] : 0;
        $sort = empty($sort) ? 'rdr_id' : $sort;
        $dir = empty($dir) ? 'DESC' : $dir;
        $search = " WHERE 1=1 ";
        if (isset($data['filter'])) {
            $filter_qry .= buildSafeFilterQuery($data['filter'], $allowedFields, $db);
        }
//            foreach ($filter as $key => $field) {
//                if ($field['data']['value'] != "") {
//                    $checkComa = strstr($field['data']['value'], ',');
//                    if ($checkComa != '') {
//                        $fiterItem = $field['data']['value'];
//                        $fiterItem = str_replace(',', "','", $fiterItem);
//                        $search .= " and ({$field['field']} IN('{$fiterItem}')) ";
//                    } else if ($field == 'rdr_deliveryModeName') {
//                    $rdr_deliveryMode = $db->getItemFromDB("SELECT COALESCE(GROUP_CONCAT(rdr_id),0) FROM retaline_delivery_rules WHERE rdr_ruleName LIKE '{$field['data']['value']}%' ");
//                    $search .= " AND rdr_ruleName IN({$rdr_deliveryMode}) ";
//                }
//                    else {
//                        $search .= " and ({$field['field']} LIKE '{$field['data']['value']}%') ";
//                    }
//                }
//            }


        $countQuery = "SELECT COUNT(*) FROM retaline_delivery_rules  {$search}";

        $listQuery = "SELECT rdr_id,rdr_ruleName,rdr_deliveryMode,rdr_calculationMode,rdr_ruleFor,is_default,"
        . "CASE WHEN rdr_deliveryMode = 1 THEN 'Courier Delivery'
            WHEN rdr_deliveryMode = 2 THEN 'Express Delivery'
            WHEN rdr_deliveryMode = 3 THEN 'Scheduled Delivery'
        END AS rdr_deliveryMode,CASE WHEN rdr_calculationMode = 1 THEN 'Distance Rate'
            WHEN rdr_calculationMode = 2 THEN 'Flat Rate'
        END AS rdr_calculationMode, CASE WHEN rdr_ruleFor = 1 THEN 'Common Rule'
            WHEN rdr_ruleFor = 2 THEN 'Store Group' WHEN rdr_ruleFor = 3 THEN 'Store'
        END AS rdr_ruleFor,rdr_isfreeDelivery,IF(rdr_isfreeDelivery = 1,rdr_isfreeDeliveryAmt,0) AS freeDelivery,rdr_isfreeDeliveryAmt FROM retaline_delivery_rules  {$search}  ORDER BY {$sort} {$dir} limit $start,$limit";

        $db->printGridJson($countQuery, $listQuery);
        break;
    case 'setDRDefault':
        $rdr_id = $_POST['rdr_id'];
        $deliveryMode = $_POST['rdr_deliveryMode'];
        switch ($deliveryMode) {
            case 'Courier Delivery':
                $rdr_deliveryMode = 1;
                break;
            case 'Express Delivery':
                $rdr_deliveryMode = 2;
                break;
            case 'Scheduled Delivery':
                $rdr_deliveryMode = 3;
                break;
        }
        $db->query('begin');
        $defaultCount = $db->getItemFromDB("SELECT COUNT(*) FROM retaline_delivery_rules WHERE is_default = 1 AND rdr_deliveryMode = {$rdr_deliveryMode}");
        if ($defaultCount > 0) {
            $status = $db->query("UPDATE retaline_delivery_rules SET is_default = 0 WHERE rdr_deliveryMode = {$rdr_deliveryMode}");
        }

        $data['is_default'] = 1;
        $data['rdr_updatedOn'] = date("Y-m-d H:i:s");
        $data['rdr_updatedBy'] = $userid;
        $status = $db->perform('retaline_delivery_rules', $data, 'update', " rdr_id = {$rdr_id}");
        $status = $db->query('commit');
        if ($status) {
            $msg = "Default updated.";
            echo '{"success":true,"valid":true,"msg":"' . $msg . '"}';
        } else {
            $msg = "Error Occured";
            echo '{"success":true,"valid":false,"msg":"' . $msg . '"}';
        }
        break;
    case 'dr_details':
        require(THIS_MODULE_PATH . "/rightpanel.php");
        break;
    case 'deliveryrules_form_load':
        $podata = $db->getFromDB("SELECT rdr_id,rdr_ruleName,rdr_deliveryMode,rdr_calculationMode,rdr_ruleFor,"
                . "CASE WHEN rdr_deliveryMode = 1 THEN 'Courier Delivery'
            WHEN rdr_deliveryMode = 2 THEN 'Express Delivery'
            WHEN rdr_deliveryMode = 3 THEN 'Scheduled Delivery'
        END AS rdr_deliveryModeName,CASE WHEN rdr_calculationMode = 1 THEN 'Distance Rate'
            WHEN rdr_calculationMode = 2 THEN 'Flat Rate'
        END AS rdr_calculationModeName, CASE WHEN rdr_ruleFor = 1 THEN 'Common Rule'
            WHEN rdr_ruleFor = 2 THEN 'Store Group' WHEN rdr_ruleFor = 3 THEN 'Store'
        END AS rdr_ruleForName,rdr_fixedRateperkm,rdr_fixedRateMin,rdr_fixedRateMax,rdr_fromkm1,rdr_tokm1,rdr_amt1,rdr_amt1,rdr_fromkm2,rdr_tokm2,rdr_amt2,rdr_fromkm3,rdr_tokm3,rdr_amt3,rdr_isfreeDelivery,
        rdr_isfreeDeliveryAmt,rdr_ruleFor,rdr_ruleForId,is_default FROM retaline_delivery_rules WHERE rdr_id ='{$_POST['rdr_id']}'", true);
        if (!empty($podata)) {
            echo json_encode($podata);
        }
        break;
}
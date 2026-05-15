<?php

/*
 * Created on 24-Jul-08
 * @author : Ratheesh Kumar CK <ratheesh@saturn.in>
 *
 * This module builds the initial UI and related settings
 */
require_once(EXTERNAL_LIBRARY_PATH);
require_once(ROOT . '/finascop_config/lib.php');
require_once(ROOT . '/finascop_config/config.php');
require_once(ROOT . '/includes/SafeQueryGuard.php');
writeLog(__FILE__);

function getIpBehindProxy() {
    global $_SERVER;
    $remote = array($_SERVER["REMOTE_ADDR"]);
    $comes_from = array("HTTP_VIA", "HTTP_X_COMING_FROM", "HTTP_X_FORWARDED_FOR", "HTTP_X_FORWARDED", "HTTP_COMING_FROM", "HTTP_FORWARDED_FOR", "HTTP_FORWARDED");
    foreach ($comes_from as $value) {
        if (isset($_SERVER[$value]) && preg_match_all("/([0-9]{1,3}\.){3,3}[0-9]{1,3}/", $_SERVER[$value], $remote_temp)) {
            $remote = array_merge($remote, $remote_temp[0]); //     Fish out IP match if ereg returns a value
        }
    }
    return join(',', $remote);
}

switch ($op) {
    case 'sendMailFromQueue':
        $logId = (int)($_GET['logId'] ?? 0);
        $rd = $db->query('select l.EmailLogId,l.Recipient,l.Subject,c.Content, c.Attachments from sys_email_log l, sys_email_log_content c where l.EmailLogId=c.EmailLogId and l.EmailLogId=' . $logId . ' and status="Waiting"');
        while ($rs = $db->fetch_array($rd)) {
            $status = send_mail($rs['Recipient'], $rs['Subject'], $rs['Content'], true, false, '', $rs['Attachments']);
            if ($status) {
                $data = array('Status' => 'Sent');
            } else {
                $data = array('Status' => 'Failed');
            }
            $db->perform('sys_email_log', $data, 'update', 'EmailLogId=' . $rs['EmailLogId']);
        }
        break;
    case 'get-mod-menu':
        echo getTopLevelButtons((int)($_POST['ParentMenuId'] ?? 0));
        break;
    case 'generateUserJs':
        $userID = (int)($_GET['userID'] ?? 0);
        require('./javascript.php');
        break;
    case 'ui-state':
        require(THIS_MODULE_PATH . "/ui-state-manager.php");
        break;
    case 'getBrochure':
        $url = REPORTSERVER . "repwebexe.bin/execute.pdf?reportname=brochure&aliasname=general&username=Admin&Password=r3pm4nk1c&metafile=0";

        $opts = array(
            CURLOPT_URL => $url,
            CURLINFO_CONTENT_TYPE => "text/xml",
            CURLOPT_BINARYTRANSFER => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE
        );

        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);


        header("Content-Type: application/pdf");
        header("Content-disposition: attachment; filename=Brochure.pdf");
        //this->data->Output('test.pdf', 'D')
        //header("Content-Type: text/plain"); 
        echo $data;

        break;
    case 'getViews':
        $whr = '';
        $sessionUserId = (int)($_SESSION['admin']->Finascop_UserId ?? 0);
        $defaultViewId = $db->getItemFromDB('SELECT Varvalue FROM usr_preference WHERE Varname = "default_view" AND UserId =' . $sessionUserId);
        if (!empty($_POST['query'])) {
            $whr = "  AND (ViewName LIKE '%" . SafeQueryGuard::escapeLike($_POST['query'], $db) . "%')";
        }
        $UserId = (int)($_POST['UserId'] ?? 0);
        $SiteId = (int)($_POST['siteid'] ?? 0);
        $qry = <<<EOT
        SELECT
                ViewId, ViewName, IsPublic, CreatedBy, Description,"Yes" as IsDefault
        FROM
                sys_views
        WHERE
               CreatedBy=$UserId AND (SiteId=$SiteId) $whr
        ORDER BY
                ViewName ASC
EOT;

        $rs = $db->query($qry);
        $i = 0;
        $num_rows = $db->num_rows($rs);
        echo "[";
        while ($row = $db->fetch_array($rs)) {
            if ($row[ViewId] == $defaultViewId) {
                $row[IsDefault] = "on";
            } else
                $row[IsDefault] = "";
            $row[ViewName] = stripslashes($row[ViewName]);
            $tmp = array($row[ViewId], $row[ViewName], $row[Description], $row[IsPublic], $row[IsDefault]);
            echo json_encode($tmp);
            $i++;
            if ($i < $num_rows)
                echo ",";
            flush();
        }
        echo "]";
        break;
    case 'getViewsDependingOnOwner':
        $userId = (int)($_SESSION['admin']->Finascop_UserId ?? 0);
        $SiteId = (int)($_POST['siteid'] ?? 0);
        $defaultViewId = $db->getItemFromDB('SELECT Varvalue FROM usr_preference WHERE Varname = "default_view" AND UserId =' . $userId);
        $qry = <<<EOT
        SELECT
                ViewId, ViewName,Description, IsPublic,"Yes" as IsDefault,CreatedBy
        FROM
                sys_views
        WHERE
                CreatedBy=$userId AND SiteId=$SiteId
        ORDER BY
                ViewName ASC
EOT;
        // Query Modified. Added one more condition on SiteId(Where clause)-Sreeram

        $rs = $db->query($qry);
        $i = 0;
        $num_rows = $db->num_rows($rs);
        echo "[";
        //echo "['-1','Default View'],";
        while ($row = $db->fetch_row($rs)) {
            if ($row[ViewId] == $defaultViewId) {
                $row[IsDefault] = "on";
            } else
                $row[IsDefault] = "";
            $row[ViewName] = stripslashes($row[ViewName]);
            $tmp = array($row[ViewId], $row[ViewName], $row[Description], $row[IsPublic], $row[IsDefault]);
            echo json_encode($tmp);
            $i++;
            if ($i < $num_rows)
                echo ",";
            flush();
        }
        echo "]";
        break;
    case 'getMenuId':
        $menuId = $_POST['menuID'];
        /*  $data = array(
          'Userid' => intval($_SESSION['admin']->Finascop_UserId),
          'IP' => getIpBehindProxy(),
          'MenuId' => $menuId,
          'loggeddate' => date("Y-m-d")
          );
          $db->perform('web_menu_usage', $data);
          echo '{"success":"true","data":' . json_encode($data) . '}'; */
        echo '{"success":"true"}';
        break;

    case 'userBranchStore':
        $data = $db->getMulipleData("select br_ID,br_Name from " . FINASCOP_DB . "finascop_branch order by br_Name", true);
        $combo = json_encode($data);
        echo '{"data":' . $combo . '}';
        break;

    case 'setSelectedBranch':
        $data = $_POST;
        $_SESSION['admin']->typdetsid = $data['branch_id'];
        echo "{success:true,valid:true,typdetsid:{$_SESSION['admin']->typdetsid}}";
        break;
    case 'mkGrid':
        $gridParams = SafeQueryGuard::validateGridParams($_POST);
        if ($gridParams === false) {
            SafeQueryGuard::abort('Invalid grid parameters');
        }
        if ($gridParams['type'] === 'dummy') {
            $data = array('total' => 0, 'data' => array());
        } else {
            $where = (!empty($gridLimitQuery[$gridParams['type']]) && !empty($_POST['limit_by'])) ? $gridLimitQuery[$gridParams['type']] : "";
            $total = $db->getItemFromDB(sprintf("SELECT count(1) FROM `%s` %s ORDER BY `%s` %s", $gridParams['type'], $where, $gridParams['sort'], $gridParams['dir']));
            $query = sprintf("SELECT %s FROM `%s` %s ORDER BY `%s` %s", $gridParams['fields'], $gridParams['type'], $where, $gridParams['sort'], $gridParams['dir']);
            $data = array('totalCount' => $total, 'data' => $db->getMultipleData($query, true));
        }
        echo json_encode($data);
        break;
    case 'mkCombo':
        $comboParams = SafeQueryGuard::validateComboParams($_POST);
        if ($comboParams === false) {
            SafeQueryGuard::abort('Invalid combo parameters');
        }

        $con = "";
        $typeAhead = checkTypeAhead($comboParams['display']);
        if (!empty($typeAhead)) {
            $con = " WHERE " . str_replace(' AND ', '', $typeAhead);
        }

        $condition_array = array('S' => '`status`', 'IA' => 'isactive', 'AA' => 'admin_active');

        if (!empty($_POST['cx'])) {
            $cn = explode('_', $_POST['cx']);
            if (isset($condition_array[$cn[0] ?? ''])) {
                if (empty($con)) {
                    $con = ' WHERE ';
                } else {
                    $con .= ' AND ';
                }
                $safeVal = (int)($cn[1] ?? 0);
                $con .= " {$condition_array[$cn[0]]} = '{$safeVal}' ";
            }
        }
        if (empty($comboParams['extraFields'])) {
            $query = sprintf("SELECT `%s`,`%s` FROM `%s` %s ORDER BY 2 ASC", $comboParams['value'], $comboParams['display'], $comboParams['type'], $con);
        } else {
            $query = sprintf("SELECT `%s`,`%s`,%s FROM `%s` %s ORDER BY 2 ASC", $comboParams['value'], $comboParams['display'], $comboParams['extraFields'], $comboParams['type'], $con);
        }
        $data = $db->getMultipleData($query);
        if (!empty($data))
            echo json_encode($data);
        else {
            echo '{"success": true,"data":[]}';
        }
        break;
    default:
        require(THIS_MODULE_PATH . "/ui.php");
        break;
}

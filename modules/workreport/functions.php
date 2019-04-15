<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2018 mynukeviet. All rights reserved
 * @Createdate Fri, 16 Mar 2018 14:11:35 GMT
 */
if (!defined('NV_SYSTEM')) die('Stop!!!');

define('NV_IS_MOD_WORKREPORT', true);

if (!defined('NV_IS_USER')) {
    $url_back = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']);
    nv_redirect_location($url_back);
}

$array_config = $module_config[$module_name];

if (!isset($site_mods['workforce'])) {
    $workforce_list = array();
    $where = !empty($array_config['work_groups']) ? ' AND userid IN (SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE group_id IN (' . $array_config['work_groups'] . '))' : '';
    $result = $db->query('SELECT userid, first_name, last_name, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE active=1' . $where);
    while ($row = $result->fetch()) {
        $row['fullname'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
        $workforce_list[$row['userid']] = $row;
    }
}

$user_groups = array();
if (defined('NV_IS_USER')) {
    $result = $db->query('SELECT group_id FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE userid=' . $user_info['userid']);
    while (list ($group_id) = $result->fetch(3)) {
        $user_groups[] = $group_id;
    }
}

$is_admin = 0;
$group_manage = !empty($array_config['admin_groups']) ? explode(',', $array_config['admin_groups']) : array();
if (!empty(array_intersect($group_manage, $user_info['in_groups']))) {
    $is_admin = 1;
}

function nv_check_action($addtime)
{
    global $array_config, $is_admin;

    if ($is_admin || ((NV_CURRENTTIME - $addtime) <= ($array_config['allow_time'] * 60))) {
        return true;
    }
    return false;
}

function nv_workreport_premission($type = 'where')
{
    global $db, $module_data, $array_config, $user_info, $workforce_list;

    $array_userid = array(); // mảng chứa userid mà người này được quản lý

    // nhóm quản lý thấy tất cả
    $group_manage = !empty($array_config['admin_groups']) ? explode(',', $array_config['admin_groups']) : array();
    if (empty(array_intersect($group_manage, $user_info['in_groups']))) {

        // kiểm tra tư cách trong nhóm (trưởng nhóm / thành viên nhóm)
        $count = 0;
        $result = $db->query('SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE is_leader=1 AND approved=1 AND userid=' . $user_info['userid']);
        while ($row = $result->fetch()) {
            $array_userid = array();
            // lấy danh sách userid thuộc nhóm do người này quản lý
            $_result = $db->query('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE approved=1 AND group_id=' . $row['group_id']);
            while (list ($userid) = $_result->fetch(3)) {
                $count++;
                $array_userid[$userid] = $userid;
            }
        }

        $array_userid = array_unique($array_userid);

        if ($type == 'where') {
            if ($count > 0) {
                // nếu là trưởng nhóm, thấy nhân viên do mình quản lý
                $array_userid = implode(',', $array_userid);
                return ' AND userid IN (' . $array_userid . ')';
            } else {
                return ' AND (userid=' . $user_info['userid'] . ')';
            }
        } elseif ($type == 'array_userid') {
            return $array_userid;
        }
    } else {
        $array_userid = !empty($workforce_list) ? array_keys($workforce_list) : array(
            0
        );
        if ($type == 'where') {
            return ' AND userid IN (' . implode(',', $array_userid) . ')';
        } elseif ($type == 'array_userid') {
            return array_keys($workforce_list);
        }
    }
}

function nv_workreport_dateDifference($date_1, $date_2, $differenceFormat = '%a')
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($differenceFormat);
}
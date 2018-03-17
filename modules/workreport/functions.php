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

$user_groups = array();
if (defined('NV_IS_USER')) {
    $result = $db->query('SELECT group_id FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE userid=' . $user_info['userid']);
    while (list ($group_id) = $result->fetch(3)) {
        $user_groups[] = $group_id;
    }
}

$is_admin = 0;
if (!empty($array_config['admin_groups']) && !empty(array_intersect($user_groups, explode(',', $array_config['admin_groups'])))) {
    $is_admin = 1;
}

$count = 0;
if (!empty($array_config['work_groups'])) {
    $count = $db->query('SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE userid = ' . $user_info['userid'] . ' AND group_id IN (' . $array_config['work_groups'] . ')')->fetchColumn();
}

if (!$is_admin && empty($count)) {
    $contents = nv_theme_alert($lang_module['title_no_premission'], $lang_module['content_no_premission'], 'danger');
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

function nv_check_action($addtime)
{
    global $array_config, $is_admin;

    if ($is_admin || ($addtime + (30 * 60))) {
        return true;
    }
    return false;
}
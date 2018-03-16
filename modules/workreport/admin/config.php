<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2018 mynukeviet. All rights reserved
 * @Createdate Fri, 16 Mar 2018 14:11:35 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$data = array();
if ($nv_Request->isset_request('savesetting', 'post')) {
    $data['per_page'] = $nv_Request->get_int('per_page', 'post', 20);

$sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . ' SET config_value = :config_value WHERE lang = '' . NV_LANG_DATA . '' AND module = :module_name AND config_name = :config_name');
    $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
    foreach ($data as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['config'], 'Config', $admin_info['userid']);
    $nv_Cache->delMod('settings');
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    die();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config);

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['config'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
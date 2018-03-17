<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 * @Createdate Wed, 13 Jul 2016 00:05:30 GMT
 */
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = $lang_module['config'];
$groups_list = nv_groups_list();

if ($nv_Request->isset_request('savesetting', 'post')) {
    $data['work_groups'] = $nv_Request->get_typed_array('work_groups', 'post', 'int');
    $data['work_groups'] = !empty($data['work_groups']) ? implode(',', $data['work_groups']) : '';
    $data['admin_groups'] = $nv_Request->get_typed_array('admin_groups', 'post', 'int');
    $data['admin_groups'] = !empty($data['work_groups']) ? implode(',', $data['admin_groups']) : '';

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
    $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
    foreach ($data as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['config'], "Config", $admin_info['userid']);
    $nv_Cache->delMod('settings');

    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . '=' . $op);
    die();
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config);

$array_config['admin_groups'] = explode(',', $array_config['admin_groups']);
foreach ($groups_list as $group_id => $grtl) {
    $_groups = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $array_config['admin_groups']) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('ADMIN_GROUPS', $_groups);
    $xtpl->parse('main.admin_groups');
}

$array_config['work_groups'] = explode(',', $array_config['work_groups']);
foreach ($groups_list as $group_id => $grtl) {
    $_groups = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $array_config['work_groups']) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('WORK_GROUPS', $_groups);
    $xtpl->parse('main.work_groups');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';

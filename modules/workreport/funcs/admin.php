<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 16 Nov 2017 13:47:46 GMT
 */
if (!defined('NV_IS_MOD_WORKREPORT')) die('Stop!!!');

if (empty($workforce_list)) {
    $contents = nv_theme_alert($lang_module['error_required_workforcelist'], $lang_module['error_required_workforcelist_content'], 'warning');
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$array_data = array();
$current_day = nv_date('dmY', NV_CURRENTTIME);
$current_day = $nv_Request->get_string('time', 'get', nv_date('d/m/Y', NV_CURRENTTIME));

$db->select('userid, content, addtime, time')
    ->from(NV_PREFIXLANG . '_' . $module_data)
    ->where('DATE_FORMAT(FROM_UNIXTIME(fortime),"%d%m%Y")=' . preg_replace('/[^0-9]+/', '', $current_day));

$sth = $db->query($db->sql());

while ($row = $sth->fetch()) {
    $row['content'] = nv_nl2br($row['content']);
    $row['addtime'] = nv_date("H:i d/m/Y", $row['addtime']);
    $array_data[$row['userid']] = $row;
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('TIME', $current_day);

if (!empty($workforce_list)) {
    foreach ($workforce_list as $userid => $user) {
        $user['content'] = isset($array_data[$userid]) ? $array_data[$userid]['content'] : '';
        $user['addtime'] = isset($array_data[$userid]) ? $array_data[$userid]['addtime'] : '';
        $user['time'] = isset($array_data[$userid]) ? $array_data[$userid]['time'] : '';
        $xtpl->assign('USER', $user);
        $xtpl->parse('main.user');
    }
}

if (!$global_config['rewrite_enable']) {
    $xtpl->assign('ACTION', NV_BASE_SITEURL . 'index.php');
} else {
    $xtpl->assign('ACTION', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['admin'], true));
}
if (!$global_config['rewrite_enable']) {
    $xtpl->parse('main.no_rewrite');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['workreport'];
$array_mod_title[] = array(
    'title' => $page_title,
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name
);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';

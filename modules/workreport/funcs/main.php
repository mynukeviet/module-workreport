<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 16 Nov 2017 13:47:46 GMT
 */

if (!defined('NV_IS_MOD_WORKREPORT')) die('Stop!!!');

if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $id = $nv_Request->get_int('delete_id', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    $redirect = $nv_Request->get_string('redirect', 'get', '');

    $addtime = $db->query('SELECT addtime FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id)->fetchColumn();

    if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id']) and nv_check_action($addtime)) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '  WHERE id = ' . $db->quote($id));
        $nv_Cache->delMod($module_name);
        if (!empty($redirect)) {
            $url = nv_redirect_decrypt($redirect);
        } else {
            $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
        }
        nv_redirect_location($url);
    }
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
$row['redirect'] = $nv_Request->get_string('redirect', 'post,get', '');

if ($nv_Request->isset_request('submit', 'post')) {
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('fortime', 'post'), $m)) {
        $_hour = 23;
        $_min = 23;
        $row['fortime'] = mktime($_hour, $_min, 59, $m[2], $m[1], $m[3]);
    } else {
        $row['fortime'] = 0;
    }
    $row['content'] = $nv_Request->get_string('content', 'post', '');

    if (empty($row['fortime'])) {
        $error[] = $lang_module['error_required_fortime'];
    } elseif (empty($row['content'])) {
        $error[] = $lang_module['error_required_content'];
    } elseif (empty($row['id']) && $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE fortime=' . $row['fortime'] . ' AND userid=' . $user_info['userid'])->fetchColumn() > 0) {
        $error[] = $lang_module['error_required_fortime'];
    }

    if (empty($error)) {
        try {
            if (empty($row['id'])) {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (userid, fortime, content, addtime) VALUES (' . $user_info['userid'] . ', :fortime, :content, ' . NV_CURRENTTIME . ')');
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET fortime = :fortime, content = :content WHERE id=' . $row['id']);
            }
            $stmt->bindParam(':fortime', $row['fortime'], PDO::PARAM_INT);
            $stmt->bindParam(':content', $row['content'], PDO::PARAM_STR, strlen($row['content']));

            $exc = $stmt->execute();
            if ($exc) {
                $nv_Cache->delMod($module_name);
                if (!empty($row['redirect'])) {
                    $url = nv_redirect_decrypt($row['redirect']);
                } else {
                    $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
                }
                nv_redirect_location($url);
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }
} elseif ($row['id'] > 0) {
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

    if (!nv_check_action($row['addtime'])) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
} else {
    $row['id'] = 0;
    $row['fortime'] = NV_CURRENTTIME;
    $row['content'] = '';
}

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$where = '';
$per_page = 20;
$page = $nv_Request->get_int('page', 'post,get', 1);
$current_month = date('m', NV_CURRENTTIME);
$array_users = array();

$array_search = array(
    'month' => $nv_Request->get_int('month', 'get', date('m', NV_CURRENTTIME)),
    'userid' => $nv_Request->get_int('userid', 'get', 0)
);

if ($is_admin) {
    $result = $db->query('SELECT t1.userid, username, last_name, first_name FROM ' . NV_USERS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . '_groups_users t2 ON t1.userid=t2.userid WHERE t2.group_id IN (' . $array_config['work_groups'] . ')');
    while ($_row = $result->fetch()) {
        $_row['fullname'] = nv_show_name_user($_row['first_name'], $_row['last_name'], $_row['username']);
        $array_users[$_row['userid']] = $_row;
    }
}

if (!empty($array_search['month'])) {
    $base_url .= '&month=' . $array_search['month'];
    $current_month = $array_search['month'];
}

if (!empty($array_search['userid'])) {
    $base_url .= '&userid=' . $array_search['userid'];
    $where .= ' AND userid=' . $array_search['userid'];
} else {
    $where .= ' AND userid=' . $user_info['userid'];
}

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '')
    ->where('DATE_FORMAT(FROM_UNIXTIME(fortime),"%m")=' . $current_month . $where);

$sth = $db->prepare($db->sql());

$sth->execute();
$num_items = $sth->fetchColumn();

$db->select('*')
    ->order('fortime DESC')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);
$sth = $db->prepare($db->sql());
$sth->execute();

$row['fortime'] = !empty($row['fortime']) ? nv_date('d/m/Y', $row['fortime']) : '';

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}
$number = $page > 1 ? ($per_page * ($page - 1)) + 1 : 1;
while ($view = $sth->fetch()) {
    $view['number'] = $number++;

    $allow_action = 0;
    if (nv_check_action($view['addtime'])) {
        $allow_action = 1;
        $view['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;id=' . $view['id'] . '&amp;redirect=' . nv_redirect_encrypt($client_info['selfurl']);
        $view['link_delete'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] . '&amp;redirect=' . nv_redirect_encrypt($client_info['selfurl']));
    }

    $view['fortime'] = (empty($view['fortime'])) ? '' : nv_date('d/m/Y', $view['fortime']);
    $view['addtime'] = (empty($view['addtime'])) ? '' : nv_date('H:i d/m/Y', $view['addtime']);
    $view['content'] = nv_nl2br($view['content']);

    $xtpl->assign('VIEW', $view);

    if ($allow_action) {
        $xtpl->parse('main.loop.action');
    }

    $xtpl->parse('main.loop');
}

for ($i = 1; $i <= 12; $i++) {
    $xtpl->assign('MONTH', array(
        'index' => $i,
        'value' => sprintf($lang_module['month'], $i),
        'selected' => $current_month == $i ? 'selected="selected"' : ''
    ));
    $xtpl->parse('main.month');
}

if ($is_admin && !empty($array_users)) {
    foreach ($array_users as $user) {
        $user['selected'] = $array_search['userid'] == $user['userid'] ? 'selected="selected"' : '';
        $xtpl->assign('USER', $user);
        $xtpl->parse('main.users.loop');
    }
    $xtpl->parse('main.users');
}

if (!$global_config['rewrite_enable']) {
    $xtpl->assign('ACTION', NV_BASE_SITEURL . 'index.php');
} else {
    $xtpl->assign('ACTION', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true));
}
if (!$global_config['rewrite_enable']) {
    $xtpl->parse('main.no_rewrite');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
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
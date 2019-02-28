<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 * @Createdate Fri, 30 Dec 2016 01:40:16 GMT
 */
define('NV_SYSTEM', true);

define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __file__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

$language_query = $db->query('SELECT lang FROM ' . $dataname . '.' . $db_config['prefix'] . '_setup_language WHERE setup = 1');
while (list ($lang) = $language_query->fetch(3)) {
    $mquery = $db->query("SELECT title, module_data FROM " . $dataname . "." . $db_config['prefix'] . "_" . $lang . "_modules WHERE module_file = 'workreport'");
    while (list ($mod, $mod_data) = $mquery->fetch(3)) {
        $_sql = array();

        $data = array(
            'allow_days' => 1,
            'type_content' => 1
        );
        foreach ($data as $config_name => $config_value) {
            $_sql[] = "INSERT INTO " . $dataname . "." . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', " . $db->quote($mod) . ", " . $db->quote($config_name) . ", " . $db->quote($config_value) . ")";
        }

        // $_sql[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_econtent (action, econtent) VALUES('cpltask', 'Xin chào <strong>&#91;USER_ADD&#93;</strong>!<br  /><br  />Công việc&nbsp;<strong>&#91;TITLE&#93;</strong> giao cho&nbsp;<strong>&#91;USER_WORKING&#93; </strong>đã hoàn thành.<br  />Dưới đây là thông tin chi tiết tại <strong>&#91;SITE_NAME&#93;</strong>:<ul><li><strong>Tiêu đề: </strong>&#91;TITLE&#93;</li><li><strong>Người giao việc:</strong> &#91;USER_ADD&#93;</li><li><strong>Người thực hiện</strong>: &#91;USER_WORKING&#93;</li><li><strong>Thời gian bắt đầu:</strong> &#91;TIME_START&#93;</li><li><strong>Thời gian hoàn thành:</strong> &#91;TIME_END&#93;</li><li><strong>Thời gian hoàn thành thực tế:</strong> &#91;TIME_REAL&#93;</li><li><strong>Trạng thái:</strong> &#91;STATUS&#93;</li></ul>&#91;CONTENT&#93;<br  /><br  />Theo dõi và cập nhật tiến độ công việc tại &#91;TASK_URL&#93;')";

        if (!empty($_sql)) {
            foreach ($_sql as $sql) {
                try {
                    $db->query($sql);
                } catch (PDOException $e) {
                    //
                }
            }
            $nv_Cache->delMod($mod);
            $nv_Cache->delMod('settings');
        }
    }
}

die('OK');

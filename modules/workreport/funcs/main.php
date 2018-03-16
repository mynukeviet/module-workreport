<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2018 mynukeviet. All rights reserved
 * @Createdate Fri, 16 Mar 2018 14:11:35 GMT
 */

if (!defined('NV_IS_MOD_WORKREPORT')) die('Stop!!!');

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$array_data = array();

$contents = nv_theme_workreport_main($array_data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';

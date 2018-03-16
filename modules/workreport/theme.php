<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2018 mynukeviet. All rights reserved
 * @Createdate Fri, 16 Mar 2018 14:11:35 GMT
 */

if (!defined('NV_IS_MOD_WORKREPORT')) die('Stop!!!');

/**
 * nv_theme_workreport_main()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_workreport_main($array_data)
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;
    
    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    
    $xtpl->parse('main');
    return $xtpl->text('main');
}
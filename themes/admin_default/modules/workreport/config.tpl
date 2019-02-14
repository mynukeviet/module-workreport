<!-- BEGIN: main -->
<form action='' method='post' class='form-horizontal'>
    <div class='panel panel-default'>
        <div class='panel-body'>
            <div class="form-group">
                <label class="col-sm-4 text-right"><strong>{LANG.config_work_groups}</strong></label>
                <div class="col-sm-20">
                    <div style="border: 1px solid #ddd; padding: 10px; height: 200px; overflow: scroll;">
                        <!-- BEGIN: work_groups -->
                        <label class="show"><input title="{WORK_GROUPS.title}" type="checkbox" name="work_groups[]" value="{WORK_GROUPS.value}" {WORK_GROUPS.checked} />{WORK_GROUPS.title}</label>
                        <!-- END: work_groups -->
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 text-right"><strong>{LANG.config_admin_groups}</strong></label>
                <div class="col-sm-20">
                    <div style="border: 1px solid #ddd; padding: 10px; height: 200px; overflow: scroll;">
                        <!-- BEGIN: admin_groups -->
                        <label class="show"><input title="{ADMIN_GROUPS.title}" type="checkbox" name="admin_groups[]" value="{ADMIN_GROUPS.value}" {ADMIN_GROUPS.checked} />{ADMIN_GROUPS.title}</label>
                        <!-- END: admin_groups -->
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.config_allow_time}</strong></label>
                <div class="col-sm-20">
                    <input type="number" name="allow_time" value="{DATA.allow_time}" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"><strong>{LANG.config_allow_days}</strong></label>
                <div class="col-sm-20">
                    <input type="number" name="allow_days" value="{DATA.allow_days}" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 col-form-label"><strong>{LANG.type_content}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-20">
                    <!-- BEGIN: type_content -->
                    <label><input id="type_content" class="form-control" type="radio" name="type_content" value="{OPTION.key}" {OPTION.checked}required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')">{OPTION.title} &nbsp;</label>
                    <!-- END: type_content -->
                </div>
            </div>
        </div>
    </div>
    <div class='text-center'>
        <input type='submit' class='btn btn-primary' value='{LANG.save}' name='savesetting' />
    </div>
</form>

<!-- END: main -->

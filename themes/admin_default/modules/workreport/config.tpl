<!-- BEGIN: main -->
<form action='' method='post' class='form-horizontal'>
    <div class='panel panel-default'>
        <div class='panel-body'>
            <div class="form-group">
                <label class="col-sm-3 text-right"><strong>{LANG.config_work_groups}</strong></label>
                <div class="col-sm-21">
                    <div style="border: 1px solid #ddd; padding: 10px; height: 200px; overflow: scroll;">
                        <!-- BEGIN: work_groups -->
                        <label class="show"><input title="{WORK_GROUPS.title}" type="checkbox" name="work_groups[]" value="{WORK_GROUPS.value}" {WORK_GROUPS.checked} />{WORK_GROUPS.title}</label>
                        <!-- END: work_groups -->
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 text-right"><strong>{LANG.config_admin_groups}</strong></label>
                <div class="col-sm-21">
                    <div style="border: 1px solid #ddd; padding: 10px; height: 200px; overflow: scroll;">
                        <!-- BEGIN: admin_groups -->
                        <label class="show"><input title="{ADMIN_GROUPS.title}" type="checkbox" name="admin_groups[]" value="{ADMIN_GROUPS.value}" {ADMIN_GROUPS.checked} />{ADMIN_GROUPS.title}</label>
                        <!-- END: admin_groups -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='text-center'>
        <input type='submit' class='btn btn-primary' value='{LANG.save}' name='savesetting' />
    </div>
</form>
<!-- END: main -->

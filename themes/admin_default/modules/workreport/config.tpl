<!-- BEGIN: main -->
<form action='' method='post' class='form-horizontal'>
    <div class='panel panel-default'>
        <div class='panel-body'>
            <div class='form-group'>
                <label class='col-sm-4 control-label'>{LANG.config_per_page}</label>
                <div class='col-sm-20'>
                    <input type='number' name='per_page' value='{DATA.per_page}' class='form-control' />
                </div>
            </div>
        </div>
    </div>
    <div class='text-center'>
        <input type='submit' class='btn btn-primary' value='{LANG.save}' name='savesetting' />
    </div>
</form>
<!-- END: main -->

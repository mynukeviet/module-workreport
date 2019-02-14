<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2-bootstrap.min.css">
<div class="well">
    <form action="{ACTION}" method="get">
        <!-- BEGIN: no_rewrite -->
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" /> <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" /> <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
        <!-- END: no_rewrite -->
        <div class="row">
            <div class="col-xs-24 col-md-3">
                <div class="form-group">
                    <select class="form-control" name="year">
                        <!-- BEGIN: year -->
                        <option value="{YEAR.index}"{YEAR.selected}>{YEAR.index}</option>
                        <!-- END: year -->
                    </select>
                </div>
            </div>
            <div class="col-xs-24 col-md-3">
                <div class="form-group">
                    <select class="form-control" name="month">
                        <!-- BEGIN: month -->
                        <option value="{MONTH.index}"{MONTH.selected}>{MONTH.value}</option>
                        <!-- END: month -->
                    </select>
                </div>
            </div>
            <!-- BEGIN: users -->
            <div class="col-xs-24 col-md-6">
                <div class="form-group">
                    <select class="form-control select2" name="userid">
                        <!-- BEGIN: loop -->
                        <option value="{USER.userid}"{USER.selected}>{USER.fullname}</option>
                        <!-- END: loop -->
                    </select>
                </div>
            </div>
            <!-- END: users -->
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
                    <!-- BEGIN: admin -->
                    <a class="btn btn-primary" href="{URL_ADMIN}">{LANG.admin}</a>
                    <!-- END: admin -->
                </div>
            </div>
        </div>
    </form>
</div>
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form class="form-horizontal" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="panel panel-default">
        <div class="panel-body">
            <input type="hidden" name="id" value="{ROW.id}" /> <input type="hidden" name="redirect" value="{ROW.redirect}" />
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.fortime}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <div class="input-group">
                        <input class="form-control required" type="text" name="fortime" value="{ROW.fortime}" id="fortime" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /> <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="fortime-btn">
                                <em class="fa fa-calendar fa-fix"> </em>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.time}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <input type="text" name="time" value="{ROW.time}" class="form-control required" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.content}</strong></label>
                <div class="col-sm-19 col-md-20 required">{ROW.content}</div>
            </div>
            <div class="form-group text-center">
                <input class="btn btn-primary " name="submit" type="submit" value="{LANG.save}" />
            </div>
        </div>
    </div>
</form>
<form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-middle">
            <thead>
                <tr>
                    <th width="50" class="text-center">{LANG.number}</th>
                    <th width="150">{LANG.fortime}</th>
                    <th width="180">{LANG.time}</th>
                    <th>{LANG.content}</th>
                    <th width="150">{LANG.addtime}</th>
                    <th width="150">&nbsp;</th>
                </tr>
            </thead>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td class="text-center" colspan="6">{NV_GENERATE_PAGE}</td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td class="text-center">{VIEW.number}</td>
                    <td class="text-center">
                        {VIEW.day_in_weeks}<br />{VIEW.fortime}
                    </td>
                    <td class="text-center">{VIEW.time}</td>
                    <td>{VIEW.content}</td>
                    <td>{VIEW.addtime}</td>
                    <td class="text-center">
                        <!-- BEGIN: action -->
                        <i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
                        <!-- END: action -->
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <thead>
                <tr>
                    <th colspan="6">{LANG.worktime}{TOTAL}</th>
                </tr>
            </thead>
        </table>
    </div>
</form>
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    //<![CDATA[
    $('.select2').select2({
        theme : 'bootstrap'
    });
    
    $("#fortime").datepicker({
        dateFormat : "dd/mm/yy",
        changeMonth : true,
        changeYear : true,
        showOtherMonths : true,
        showOn : "focus",
        yearRange : "-90:+5",
    });

    //]]>
</script>
<!-- END: main -->
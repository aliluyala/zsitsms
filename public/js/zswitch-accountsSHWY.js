var account = {
    call : function(url){
        window.location = url;
    },
    isRepeat : function(vin,recordid){

        $.post("index.php?module=AccountsSHWY&action=isRepeat","vin="+vin+"&recordid="+recordid,function(result){
            if(result.type == 'error'){
                var info = "<span style='font-weight:bold;line-height:20px;'>"+result.data+"</span>";
                zswitch_open_messagebox("editview_repeat_errorr","车架号重复",info,150,400);
            }
        },'json');
    },
    isValidity : function(vin,recordid){

        $.post("index.php?module=AccountsSHWY&action=isRepeat","vin="+vin.val()+"&recordid="+recordid,function(result){
            var info = "";
            info += zswitchui_validity_check("#form_edit_view");
            if(result.type == 'error'){
                info += "<span style='line-height:20px;'>"+result.data+"</span><br />";
                vin.parent().prev().children("label").css({"font-style":"italic","color":"#FF0000"});
                //zswitch_open_messagebox("editview_repeat_errorr","车架号重复",info,150,400);
            }else{
                vin.parent().prev().children("label").css({"font-style":"normal","color":"#5f5f5f"});
            }
            if(info.length<=0)
            {
                $("#dialog_confirm_save").dialog("open");
            }
            else
            {
                info = "<span style='font-weight:bold;line-height:20px;'>以下字段输入无效，请核对。</span>" +"<div style='margin-left:25px;'><br/>"+info+"</div>";
                zswitch_open_messagebox("editview_input_errorr","输入错误",info,400,400);
            }
        },'json');
    },
    set : function(acid){

        var dt = new Date();
        var id = $.md5("a"+dt.getTime());
        var dlgbox = '<div id="'+id+'"></div>';
        $("#main_view_client").append(dlgbox);
        var cont = $("#"+id);
        cont.append('<div id="progressbar" style="height:16px;width:350px;margin-top:160px;margin-left:165px"></div>');
        var prg = cont.find("#progressbar");
        prg.progressbar({value: false});
        $("#"+id).data("recordid",acid);
        $("#"+id).dialog({
        title:'去年保险记录',
        autoOpen: true,
        height: 430,
        width: 700,
        modal: true,
        appendTo: "#main_view_client",
        dialogClass:"dialog_default_class",
        open:function(){
            var url ="index.php?module=Insurance&action=lastyearview&recordid="+$(this).data("recordid");
            $(this).load(url);
        },
        beforeClose:function(){
            $(this).dialog("destroy");
            $(this).remove();
        }

    });
    },
    //无效id
    tip_popup : function(accid){
        if(!$('#main_view_client').children().is('#detail_add_track_popup_dlg'))
        {
            var html = '<div id="detail_add_track_popup_dlg"></div>';
            $('#main_view_client').append(html);
        }

        $('#detail_add_track_popup_dlg').data('accountid',accid);
        $('#detail_add_track_popup_dlg').dialog({
            autoOpen:true,
            height:280,
            width:500,
            modal:true,
            title:"提示",
            appendTo: "#main_view_client",
            dialogClass:"dialog_default_class",
            open:function(){
                zswitch_show_progressbar($(this),"import_progress");
                var url ="index.php?module=AccountTrackSHWY&action=addTrackPopup&accountid="+$('#detail_add_track_popup_dlg').data('accountid');
                $(this).load(url);
            },
            beforeClose:function(){
                $(this).dialog("destroy");
                $(this).remove();
				zswitch_load_client_view("index.php?module=AccountSHWY&action=detailView&recordid="+ $('#detail_add_track_popup_dlg').data('accountid')+"&return_module=AccountSHWY&return_action=index");
            },
            buttons: {
            "确定":function(){
                $( this ).dialog( "close" );
            }
            }
        });
    },
    goEditPage : function(module,recordid){
        zswitch_load_client_view("index.php?module="+module+"&action=editView&recordid="+recordid+"&return_module="+module+"&return_action=detailView&return_recordid="+recordid);
    },
    track_popup_run : function(module,accid,isEditPage){
        accid == 0 || accid == -1 ? account.tip_popup(accid) : (isEditPage ? account.goEditPage(module,accid) : account.add_track_popup(accid));
    },
    //增加跟踪记录对话框
    add_track_popup : function(accid){
        if(!$('#main_view_client').children().is('#detail_add_track_popup_dlg'))
        {
            var html = '<div id="detail_add_track_popup_dlg"></div>';
            $('#main_view_client').append(html);
        }

        $('#detail_add_track_popup_dlg').data('accountid',accid);
        $('#detail_add_track_popup_dlg').dialog({
            autoOpen:true,
            height:280,
            width:500,
            modal:true,
            title:"添加跟踪记录",
            appendTo: "#main_view_client",
            dialogClass:"dialog_default_class",
            open:function(){
                zswitch_show_progressbar($(this),"import_progress");
                var url ="index.php?module=AccountTrackSHWY&action=addTrackPopup&accountid="+$('#detail_add_track_popup_dlg').data('accountid');
                $(this).load(url);
            },
            beforeClose:function(){
                $(this).dialog("destroy");
                $(this).remove();
            },
            buttons: {
            "确定":function(){
                var info = zswitchui_validity_check("#detailview_account_track_add_dlg_form");
                if(($("#detailview_account_track_add_dlg_form select[name='status']").val() == "APPOINTMENT_QUOTATION" || $("#detailview_account_track_add_dlg_form select[name='status']").val() == "APPOINTMENT_NON_QUOTATION")
                    && ($("#detailview_account_track_add_dlg_form input[name='preset_time']").val() == '' || $("#detailview_account_track_add_dlg_form input[name='preset_time']").val() == 'undefined')){
                    $("#detailview_account_track_add_dlg_form label[for='preset_time']").css({"color":"#ff0000","font-style":"italic"});
                    info = $("#detailview_account_track_add_dlg_form input[name='preset_time']").attr("label")+"：不能为空。";
                }
                if(info.length<=0)
                {
                    zswitch_show_progressbar($(this),"import_progress");
                    var url = "index.php?module=AccountTrackSHWY&action=save";
                    $.post(url,$("#detailview_account_track_add_dlg_form").serialize(),function(){
                            $('#detail_add_track_popup_dlg').dialog("close");
                    });
                }
                else
                {
                    info = "<span style='font-weight:bold;line-height:20px;'>以下字段输入无效，请核对。</span>" +"<div style='margin-left:25px;'><br/>"+info+"</div>";
                    zswitch_open_messagebox("editview_input_errorr","输入错误",info,200,400);
                }

            },
            "取消":function(){
                $( this ).dialog( "close" );
            }
            }
        });
    }
};
var telemarketing = {
    //禁止按钮
    disable_button : function(recordid){
        if(recordid == 0 || recordid == -1){
            $(".client_detailview_operation_button").each(function(){
                if($(this).attr("action") != "next"){
                    $(this).attr('disabled', 'disabled');
                    $(this).addClass('ui-state-disabled');
                }
            });
        }
    },
};
var Insurance = {
    disabledSave : function(accountid){
        if(accountid == 0 || accountid == -1){
            $(".module_seting_button").each(function(){
                if($(this).attr("operation") == "save"){
                    $(this).attr('disabled', 'disabled');
                    $(this).addClass('ui-state-disabled');
                }
            });
        }
    },
};
var VehicleInfo = {
    search : function(page,model){
         zswitch_show_progressbar($("#query_purchaseprice_dlg"),"import_progress");
         model = model == '' ? $('#serach_input').val() : model;
         var url = encodeURI("index.php?module=Insurance&action=queryPurchasePrice&model=" + model + "&page=" + page);
         $("#query_purchaseprice_dlg").load(url);
    }
};

var handout = {
    set : function(mod){
        var dt = new Date();
        var id = $.md5("a"+dt.getTime());
        var dlgbox = '<div id="'+id+'"></div>';
        $("#main_view_client").append(dlgbox);
        this.handoutdlg = $("#" + id);
        this.handoutdlg.append('<div id="progressbar" style="height:16px;width:350px;margin-top:160px;margin-left:165px"></div>');
        var prg = this.handoutdlg.find("#progressbar");
        prg.progressbar({value: false});


        //$("#"+id).data("recordid",acid);
        $("#"+id).dialog({
        title:'分发人员',
        autoOpen: false,
        height: 290,
        width: 400,
        modal: false,
        appendTo: "#main_view_client",
        dialogClass:"dialog_default_class",
        close : function(){
            handout.isshow = false;
        },
        buttons: {
            "确定":function(){
                    ImportJs.uploadAndParse();
                    handout.hide();
                },
            "取消":function(){
                    //$( this ).dialog( "close" );
                    handout.hide();
                }
        },
        open:function(){
            var url ="index.php?module=" + mod + "&action=handoutView";
            $(this).load(url);
        },
        beforeClose:function(){
            $(this).dialog("destroy");
            $(this).remove();
        }

    });
    this.isshow = false;
    this.show = function(){
        if(!this.isshow)
        {
            this.handoutdlg.dialog("open");
            this.isshow = true;
        }
    };
    this.hide = function(){
        if(this.isshow)
        {
            this.handoutdlg.dialog("close");
            this.isshow = false;
        }
    };
    this.moveToObj = function(selecter){
        this.handoutdlg.dialog("option", "position", { my: "right top", at: "right bottom", of: selecter } );
    };
    },
    del : function(mod,num){
        var container =$('#main_view_client');
        var dlg = container.find("#dialog_confirm_delete_record").remove();

        var html = '<div id="dialog_confirm_delete_record" title="确认删除" >';
            html += '<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>';
            html += '你确定要删除<span style="color:red;font-weight:bold;">所有已筛选的记录('+num+'条)</span>吗？<br/>点击“确定”删除，点击“取消”忽略。</p></div>';

        container.append(html);
        dlg = container.find("#dialog_confirm_delete_record");
        dlg.dialog({
         autoOpen: true,
         height: 200,
         width: 380,
         modal: true,
         appendTo: "#main_view_client",
         dialogClass:"dialog_default_class",
         buttons: {
        "确定":function(){
            handout.step3PopUp(mod);
            //zswitch_load_client_view("index.php?module="+mod+"&action=step3","form_edit_view");
            /*var url = "index.php?module="+mod+"&action=step3&recordid="+dlg.data('recordid');
            zswitch_ajax_load_client_view(url);*/
            $( this ).dialog( "close" );
        },
        "取消":function(){
            $( this ).dialog( "close" );
           }
         }
        });
    },
    step3PopUp : function(mod){
        if(!$('#main_view_client').children().is('#detail_step3_popup_dlg'))
        {
            var html = '<div id="detail_step3_popup_dlg"></div>';
            $('#main_view_client').append(html);
        }

        $('#detail_step3_popup_dlg').dialog({
            autoOpen:true,
            height:280,
            width:500,
            modal:true,
            title:"执行结果",
            appendTo: "#main_view_client",
            dialogClass:"dialog_default_class",
            open:function(){
                zswitch_show_progressbar($(this),"import_progress");
                var url = "index.php?module="+mod+"&action=step3";
                $.post(url,$('#form_edit_view').serialize(),function(data){
                    $("#detail_step3_popup_dlg").html(data);
                });
            },
            beforeClose:function(){
                $(this).dialog("destroy");
                $(this).remove();
            },
            buttons: {
                "确定":function(){
                    zswitch_load_client_view("index.php?module="+mod+"&action=step1");
                    $( this ).dialog( "close" );
                }
            }
        });
    },
    breathingLight : function(option,obj){
        if(option == "on")
            obj.addClass("turn_on");
        else
            obj.removeClass("turn_on");
    },
    addSourcePopup : function(mod){
        if(!$('#main_view_client').children().is('#detail_add_source_popup_dlg'))
        {
            var html = '<div id="detail_add_source_popup_dlg"></div>';
            $('#main_view_client').append(html);
        }

        $('#detail_add_source_popup_dlg').dialog({
            autoOpen:true,
            height:280,
            width:500,
            modal:true,
            title:"名单数据来源",
            appendTo: "#main_view_client",
            dialogClass:"dialog_default_class",
            open:function(){
                zswitch_show_progressbar($(this),"import_progress");
                var url ="index.php?module=" + mod + "&action=sourcePopup";
                $.post(url,$('#form_edit_view').serialize(),function(data){
                    $("#detail_add_source_popup_dlg").html(data);
                });
            },
            beforeClose:function(){
                $(this).dialog("destroy");
                $(this).remove();
            },
            buttons: {
                "确定":function(){
                    $( this ).dialog( "close" );
                }
            }
        });
    },
    getCount : function(mod){
        zswitch_ajax_request("index.php?module="+mod+"&action=ajaxCount","form_edit_view",function(type,data){
            $("#handout_wait").text(data);
            $("#handout_wait").addClass("turn_on");
            handout.breathingLight('on',$('#handout_wait'));
            setTimeout("handout.breathingLight('off',$('#handout_wait'))",3000);
        });
    },
    handoutStep : function(mod){
        var info = zswitchui_validity_check("#form_edit_view");
        info += this.validity();
        if(info.length<=0){
            handout.step3PopUp(mod);
            //zswitch_load_client_view("index.php?module="+mod+"&action=step3","form_edit_view");
        }
        else{
            info = "<span style='font-weight:bold;line-height:20px;'>以下字段输入无效，请核对。</span>" +"<div style='margin-left:25px;'><br/>"+info+"</div>";
            zswitch_open_messagebox("editview_input_errorr","输入错误",info,400,400);
        }
    },
    recycle : function(mod){
        var info = zswitchui_validity_check("#form_edit_view");
        if(info.length<=0){
            handout.step3PopUp(mod);
            //zswitch_load_client_view("index.php?module="+mod+"&action=step3","form_edit_view");
        }
        else{
            info = "<span style='font-weight:bold;line-height:20px;'>以下字段输入无效，请核对。</span>" +"<div style='margin-left:25px;'><br/>"+info+"</div>";
            zswitch_open_messagebox("editview_input_errorr","输入错误",info,400,400);
        }
    },
    validity : function(){
        var zswitchui_validity_check_info = "";
        if($("input[name='handout_way']:checked").val() == 'HANDOUT_BY_SIT'){
            var label = $("#handout_sit").parent().parent().prev().children("label");
            if($("#handout_sit").val().length<=0){
                error = $("input[name='handout_sit']").attr("label")+"：不能为空。";
                label.css("font-style","italic");
                label.css("color","#FF0000");
                zswitchui_validity_check_info += "<span style='line-height:18px;'>"+error + "</span><br/>";
            }else{
                label.css("font-style","normal");
                label.css("color","#5f5f5f");
            }
        }else{
            var label = $("#handout_group").parent().parent().prev().children("label");
            if($("#handout_group").val().length<=0){
                error = $("input[name='handout_group']").attr("label")+"：不能为空。";
                label.css("font-style","italic");
                label.css("color","#FF0000");
                zswitchui_validity_check_info += "<span style='line-height:18px;'>"+error + "</span><br/>";
            }else{
                label.css("font-style","normal");
                label.css("color","#5f5f5f");
            }
        }
        return zswitchui_validity_check_info;
    }
};

/*
    Multiple submit.
    Copy from Vtigercrm5.4
    Modified by Cosmo.Yu
    2013-7-16 8:18:08
*/
//from vtigercrm540/include/js/general.js
if (document.all)
    var browser_ie=true
else if (document.layers)
    var browser_nn4=true
else if (document.layers || (!document.all && document.getElementById))
    var browser_nn6=true

function copySelectedOptions(source, destination) {
    var srcObj = document.getElementById(source);
    var destObj = document.getElementById(destination);

    if(typeof(srcObj) == 'undefined' || typeof(destObj) == 'undefined') return;

    for (i=0;i<srcObj.length;i++) {
        if (srcObj.options[i].selected==true) {
            var rowFound=false;
            var existingObj=null;
            for (j=0;j<destObj.length;j++) {
                if (destObj.options[j].value==srcObj.options[i].value) {
                    rowFound=true
                    existingObj=destObj.options[j]
                    break
                }
            }

            if (rowFound!=true) {
                var newColObj=document.createElement("OPTION")
                newColObj.value=srcObj.options[i].value
                if (browser_ie) newColObj.innerText=srcObj.options[i].innerText
                else if (browser_nn4 || browser_nn6) newColObj.text=srcObj.options[i].text
                destObj.appendChild(newColObj)
                srcObj.options[i].selected=false
                newColObj.selected=true
                rowFound=false
            } else {
                if(existingObj != null) existingObj.selected=true
            }
        }
    }
}
function setSelectedOptions(){
    var show = $("#handout_sit").val().split(",");
    var idlist = $("input[name='handout_sit']").val().split(",");
    $.each(show,function(index,value){
        $("#selected_merge_fields").append("<option value="+ idlist[index] +">"+ value +"</option>");
    });
}
function removeSelectedOptions(objName) {
    var obj = getObj(objName);
    if(obj == null || typeof(obj) == 'undefined') return;

    for (i=obj.options.length-1;i>=0;i--) {
        if (obj.options[i].selected == true) {
            obj.options[i] = null;
        }
    }
}
function getObj(n,d) {
  var p,i,x;
  if(!d)d=document;
  if((p=n.indexOf("?"))>0&&parent.frames.length) {d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all)x=d.all[n];
  for(i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++)  x=getObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n);
  return x;
}
function convertOptionsToJSONArray(objName,targetObjName) {
    var obj = document.getElementById(objName);
    var arr = [];
    var label = [];
    if(typeof(obj) != 'undefined') {
        for (i=0; i<obj.options.length; ++i) {
            arr.push(obj.options[i].value);
            label.push(obj.options[i].text);
        }
    }
    if(targetObjName != 'undefined') {
        var targetObj = document.getElementById(targetObjName);
        var hideEle = $("input[name='handout_sit']").get(0);
        //Do not use json data.Just use "n" to split it.
        //if(typeof(targetObj) != 'undefined') targetObj.value = JSON.stringify(arr);
        if(typeof(targetObj) != 'undefined') targetObj.value = label.join(",");
        if(typeof(hideEle) != 'undefined') hideEle.value = arr.join(",");
    }
    return arr;
}
/*function convertSelectOptionsToJSONArray(objName,targetObjName){
    var obj = document.getElementById(objName);
    var arr = [];
    var label = [];
    if(typeof(obj) != 'undefined') {
        for (i=0; i<obj.options.length; ++i) {
            if(obj.options[i].selected){
                arr.push(obj.options[i].value);
                label.push(obj.options[i].text);
            }
        }
    }
    if(targetObjName != 'undefined') {
        var targetObj = document.getElementById(targetObjName);
        var hideEle = $("input[name='handout_sit']").get(0);
        //Do not use json data.Just use "n" to split it.
        //if(typeof(targetObj) != 'undefined') targetObj.value = JSON.stringify(arr);
        if(typeof(targetObj) != 'undefined') targetObj.value = label.join(",");
        if(typeof(hideEle) != 'undefined') hideEle.value = arr.join(",");
    }
    return arr;
}*/
//from vtigercrm540/modules/Import/resources/Import.js
if (typeof(ImportJs) == 'undefined') {
    ImportJs = {
        uploadAndParse: function() {
            //if(document.getElementById("selected_merge_fields"))
                convertOptionsToJSONArray('selected_merge_fields', 'handout_sit');
            //else
            //    convertSelectOptionsToJSONArray('available_fields', 'handout_sit');
            return true;
        }
    }
}
/*
function getUsersByGroupid(obj){
    var groups = $(obj).get(0);//JQuery obj to Dom
    var index = groups.selectedIndex;//Get selected index
    var text = groups.options[index].text;//Get text
    var value = groups.options[index].value;//Get value
    var url = "index.php?module=HandOut&action=handoutView";
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'text',
        data: "recordid="+value,
        success : function(response){
            $("#available_fields").html(response);
        }
    });
}
//在此绑定在ie8及以下无法执行,故移到handoutView.tpl
$("#available_groups").bind("click","option",function(){
    var text = $(this).text();
    var value = $(this).val();
    var url = "index.php?module=HandOut&action=handoutView";
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'text',
        data: "recordid="+value,
        success : function(response){
            $("#available_fields_td").html(response);
        }
    });
});
*/
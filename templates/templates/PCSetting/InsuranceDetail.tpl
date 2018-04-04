<div id ='list_div'>
<table id="pcsetting_list"></table>
<div id="pcsetting_list_pager"></div>
<script>
var datas = '{$DATAS}';
var insurance = '{$INSURANCES}';
var icons = '{$ICONS}';
{literal}
$(function(){
    pageInit();
});

function pageInit(){

    var jqdata = decodeURI(datas);
    var parse = $.parseJSON( jqdata );
    var iconsresult = decodeURI(icons);
    var keys = $.parseJSON( iconsresult );
    var names=[];
    var model=[];
    var lastSel;
    $.each(keys, function(k,v) {
    	names.push(v);
    })
    if( typeof parse[0] != "undefined")
    {
      $.each(parse[0], function(key,value) {
        switch(key)
        {
          case 'id':
          model.push({
            name : key,
            index : key,
            width : 90,
            hidden:true
          });
          break;
          case 'status':
          model.push({
            name : key,
            index : key,
            width : 90,
          });
          break;
          case 'allot':
          model.push({
            name : key,
            index : key,
            width : 90,
          });
          break;
          default:
          model.push({
            name : key,
            index : key,
            width : 90,
            editable : true,
            editoptions:{size :10}
          });
        }
      });
    }
    else
    {
     $.each(parse, function(key,value) {
      	switch(key)
        {
          case 'id':
          model.push({
            name : key,
            index : key,
            width : 90,
            hidden:true
          });
          break;
          case 'status':
          model.push({
            name : key,
            index : key,
            width : 90,
          });
          break;
          case 'allot':
          model.push({
            name : key,
            index : key,
            width : 90,
          });
          break;
          default:
          model.push({
            name : key,
            index : key,
            width : 90,
            editable : true,
            editoptions:{size :10}
          });
        }
     })
    }
    jQuery("#pcsetting_list").jqGrid(
    {
      colNames : names,
      colModel : model,
      rowNum : 10,
      width:800,
      rowList : [ 10, 20, 30 ],
      pager : '#pcsetting_list_pager',
      sortname : 'id',
      sortorder : "desc",
      mtype : "post",
      viewrecords : true,
      cellEdit: false,
      caption : "账号信息",
      editurl : "index.php?module=PCSetting&action=insuranceDetail&insurance="+insurance,
      afterInsertRow : function(rowid, aData)
      {
         switch (aData.status)
         {
            case '正常':
              jQuery("#pcsetting_list").jqGrid('setCell', rowid, 'status',
                  '', {
                    color : 'green'
                  });
              break;
            default :
              jQuery("#pcsetting_list").jqGrid('setCell', rowid, 'status',
                  '', {
                    color : 'red'
                  });
              break;
          }
       }
    });

    jQuery("#pcsetting_list").jqGrid('navGrid','#pcsetting_list_pager',{
      del:true,
      search:true,
      add:true,
      edit:true,
      searchtext:"查找",
      addtext:"添加",
      edittext:"编辑",
      deltext:"删除",
      refreshtext:"刷新",

    },{
            closeOnEscape: true,
            reloadAfterSubmit :true,
            drag :true,
            afterSubmit :function (response, postdata) {
                var respen = JSON.parse(response.responseText);
                if(respen.type == 'error')
                {
                  alert(respen.data);
                  return false;
                }
                else
                {
                  alert('修改成功!');
                  var url ="index.php?module=PCSetting&action=insuranceDetail&insurance="+insurance;
                  $("#pcsetting_list").load("index.php?module=PCSetting&action=insuranceDetail&insurance="+insurance);
                  $("#editmodpcsetting_list").remove();
                  $(".ui-widget-overlay").remove();
                }
            },
            editData: {
                id: function () {
                    var sel_id = $('#pcsetting_list').jqGrid('getGridParam', 'selrow') ;
                    var value = $('#pcsetting_list').jqGrid('getCell',sel_id, 'id');
                    return value;
                }
            }
        },{
            closeAfterAdd: true,
            afterSubmit:function (response, postdata) {
                var respen = JSON.parse(response.responseText);
                if(respen.type == 'error')
                {
                  alert(respen.data);
                  return false;
                }
                else
                {
                  alert('新增成功!');
                  var url ="index.php?module=PCSetting&action=insuranceDetail&insurance="+insurance;
                  $("#pcsetting_list").load("index.php?module=PCSetting&action=insuranceDetail&insurance="+insurance);
                  $("#editmodpcsetting_list").remove();
                  $(".ui-widget-overlay").remove();
                }
            }
        },{ //DELETE
            closeAfterDelete :true,
            reloadAfterSubmit:true,
            afterSubmit:function (response, postdata) {
                var respen = JSON.parse(response.responseText);
                if(respen.type == 'error')
                {
                  alert(respen.data);
                  return false;
                }
                else
                {
                  alert('删除成功!');
                  var url ="index.php?module=PCSetting&action=insuranceDetail&insurance="+insurance;
                  $("#pcsetting_list").load("index.php?module=PCSetting&action=insuranceDetail&insurance="+insurance);
                  $("#delmodpcsetting_list").remove();
                  $(".ui-widget-overlay").remove();
                }
            },
            delData: {
                delid: function () {
                    var sel_id = $('#pcsetting_list').jqGrid('getGridParam', 'selrow');
                    var value = $('#pcsetting_list').jqGrid('getCell',sel_id, 'id');
                    return value;
                }
            }
        },{//SEARCH
            multipleSearch:true,
            afterSubmit:function (response, postdata) {
               alert(response)
            }
        });

    for (var i = 0; i <= parse.length; i++)
    {
        jQuery("#pcsetting_list").jqGrid('addRowData', i + 1, parse[i]);
    }
}



{/literal}
</script>
</div>
/*!
 *	APC upload file  v1.0
 *  qq:674822668
 *  email:674822668@qq.com
 *
 *  Includes 
 *           jquery-1.9.1.js
 *           jquery-ui-1.10.3.custom.min.js
 *           jquery.md5.js
 *           jquery.json-2.4.min.js
 *           
 *  Copyright 2015 Tang DaYong.
 */
 
 (function($){
	$.widget("custom.APCUpload",{
				
		options:{
			//类型：单文件，还是多文件,"single","multiple".
			type:"single",
			uploadUrl:null,
			progressUrl:null,
			apcKeyName:"APC_UPLOAD_PROGRESS",
			filter:null,
			complete:null	
		},
		
		//重载设置
		_setOption: function( key, value ) {
			if(key === 'type')
			{
				return;
			}
			else if(key === 'uploadUrl')
			{
				$("form[target='APCUploadFrameId"+this.boxid+"']").attr("action",value);				
			}
			else if(key === 'apcKeyName')
			{
				$("form[target='APCUploadFrameId"+this.boxid+"']").find("input[apcKeyName]").attr("name",value);
			}
			else if(key === 'filter')
			{
				$("form[target='APCUploadFrameId"+this.boxid+"']").find(":file").attr("accept",value);
			}
			this._super( key, value );
		},		

		//创建iframe元素
		_createUploadIframe: function(boxid){
			var frameid = "APCUploadFrameId"+boxid;
			var framehtml = '<iframe id="'+frameid+'"  name="'+frameid+'" boxid="'+boxid+'" src="javascript:void(0);" style="position:absolute; top:-9999px; left:-9999px"/>';
			$("body").append(framehtml);
			$("#"+frameid).html('');
			return $("#"+frameid);			    
		},
		//创建form元素
		_createUploadForm: function(boxobj){
			if(boxobj == null) return false;
			var boxid = boxobj.boxid;
			var d=new Date();
			var id = $.md5("a"+d.getTime());	
			var formid = "APCUploadFormId"+id;
			var fileid = "APCUploadFileId"+id;
			
			var html = '<form id="'+formid+'" action="';
			if(typeof(boxobj.options.uploadUrl) != "undefined") html += boxobj.options.uploadUrl;
			
			html += '" method="post" enctype="multipart/form-data" ';
			html += 'target="APCUploadFrameId'+boxid+'" ';
			html += 'style="position:absolute; top:-1200px; left:-1200px" >';
			html += '<input type="text" apcKeyName="apcKeyName" name="'+boxobj.options.apcKeyName+'" value="'+fileid+'" />';
			html += '<input type="file" id="'+fileid+'" name="'+fileid+'" boxid="'+boxid+'" ';
			if(boxobj.options.filter != null) html += 'accept="'+boxobj.options.filter+'"';
			html += ' /></form>';
			$("body").append(html);
			if(boxobj.options.type == "single")
			{
				$("#"+formid).children(":file").bind("change",{boxobj:boxobj},boxobj._onSelectFileChangeSingle);	
			}
			else if(boxobj.options.type == "multiple")
			{
				$("#"+formid).children(":file").bind("change",{boxobj:boxobj},boxobj._onSelectFileChangeMult);	
			}		
			return $("#"+formid);
		},
		//选取文件改变事件处理函数
		_onSelectFileChangeSingle: function(event){
			var file = this.value.split("\\").pop();
			if(!file) return ;
			var boxobj = event.data.boxobj;
			var d=new Date();
			var formid = boxobj.uploadFormObj.attr("id");
			var fileid = $.md5(file+d.getTime());	
			
			var html = '<div id="'+fileid+'" uploadformid="'+formid+'" style="margin-top:1px;border:1px solid #1E90FF;padding:2px;line-height:20px;font-size:12px" >';
			html += '<span>'+file+'</span></div>';
			
			boxobj.element.find("#upload_file_list_box")
				          .html(html);		
		},
		//选取文件改变事件处理函数
		_onSelectFileChangeMult: function(event){
			var file = this.value.split("\\").pop();
			if(!file) return;
			var boxobj = event.data.boxobj;
			var d=new Date();
			
			var formid = boxobj.uploadFormObj.attr("id");
			boxobj.uploadFormObjSel.push(formid);
		
			boxobj.uploadFormObj = boxobj._createUploadForm(boxobj);
			
			var fileid = $.md5(file+d.getTime());	
			
			var html = '<div id="'+fileid+'" uploadformid="'+formid+'" style="margin-top:1px;border:1px solid #1E90FF;padding:0px;line-height:20px;font-size:12px" ><table border="0" width="100%"><tr>';
			html += '<td><span>'+file+'</span></td> <td width="20px"> <button title="取消上传" class="ui-state-error ui-corner-all" style="margin:1px;padding:0px;">';
			html += '<span class="ui-icon ui-icon-closethick"></span></button></td></tr></table></div>';
			
			boxobj.element.find("#upload_file_list_box")
				          .append(html)
						  .find("#"+fileid+" button")
						  .bind("click",{boxobj:boxobj,fileid:fileid,formid:formid},function(event){
								$("div#"+event.data.fileid).remove();
								$("#"+event.data.formid).remove();
								for(index in boxobj.uploadFormObjSel)
								{
									if(boxobj.uploadFormObjSel[index] == event.data.formid)
									{
										boxobj.uploadFormObjSel.splice(index,1);
										break;
									}
								}	
								
						  });		
		},
		
		//进度改变
		_onPorgressChange: function(data){
			var formid = "APCUploadFormId"+data.apckey.substr(15);	
			var filebox = $("div[uploadformid='"+formid+"']");
			var currstr = "0";
			var totalstr = "0";
			if(data.current > 1024*1024)
			{
				currstr = Number(data.current/(1024*1024)).toFixed(2) + "M";
			}
			else if(data.current > 1024)
			{
				currstr = Number(data.current/1024).toFixed(2) + "K";
			}
			else
			{
				currstr = data.current;
			}	
			
			if(data.total > 1024*1024)
			{
				totalstr = Number(data.total/(1024*1024)).toFixed(2) + "M";
			}
			else if(data.total > 1024)
			{
				totalstr = Number(data.total/1024).toFixed(2) + "K";
			}
			else
			{
				totalstr = data.total;
			}				
			filebox.find("#progress_value").html(" ["+currstr+"/"+totalstr+"] ");
			var rate = (data.current/data.total)*100;
			filebox.progressbar("value",rate);					
		},
		
		//上传进程
		_onUploadHandler: function(){
			
			if(this.currentFormid == '')
			{
				while(true)
				{
					var formid = this.uploadFormObjSel.shift();
					if((typeof formid) != 'undefined')
					{
						this.currentFormid = formid;
						this.uploadIframeObj.contents().find("body").html('');						
						$("#"+formid).submit();	
						$("div[uploadformid='"+this.currentFormid+"']").find("#progress_status").html("[上传中......]");	
						break;
					}					
					else 
					{
						//this.element.find("button#select_file_button").button( "option", "disabled", false );
						//this.element.find("button#upload_file_button").button( "option", "disabled", false );
						//this.element.find("button#add_file_button").button( "option", "disabled", false );	
						var e = $.Event("complete");	
						this._trigger('complete',e,{uploadStatus:this.uploadStatus});
						return;
					}
					
				}
			}			
			else
			{	
				
				var apckey = $("#"+this.currentFormid).find(":file").attr("id");

				$.getJSON(this.options.progressUrl,{apckey:apckey},this._onPorgressChange);
				
				var rethtml = this.uploadIframeObj.contents().find("body").html();
				
				if( rethtml != '' )
				{
					var result = eval("("+rethtml+")");
					var proginfobox = $("div[uploadformid='"+this.currentFormid+"']").find("#progress_status");
					
					if(result.error == 0)
					{
						proginfobox.html("["+result.msg+"]");
						proginfobox.css("color","#00ff00");
						this.currentFormid = '';
						var inst = false;
						for(index in this.uploadStatus )
						{
							if(this.uploadStatus[index].file == result.file)
							{
								inst = true;
								break;
							}
						}
						if(!inst)
						{
							this.uploadStatus.push(result);
						}
					}
					else
					{
						proginfobox.html("["+result.msg+"]");
						proginfobox.css("color","#ff0000");
						this.currentFormid = '';
						var inst = false;
						for(index in this.uploadStatus )
						{
							if(this.uploadStatus[index].file == result.file)
							{
								inst = true;
								break;
							}
						}
						if(!inst)
						{
							this.uploadStatus.push(result);
						}						
					}
					
					
				}				
			}
			this._delay(this._onUploadHandler,500);
		},
		//开始上传
		_onStartUpload: function(event){
			var boxobj = event.data.boxobj;
			boxobj.currentFormid = '';
			var isHaveFile = false;
			boxobj.uploadIframeObj.contents().find("body").html('');
			if(boxobj.options.type == 'single' &&  boxobj.uploadFormObjSel.length>0 )
			{
				var filename = $("#"+boxobj.uploadFormObjSel[0]).find(":file").val();
				if(filename.length>0)
				{
					//选择了文件
					boxobj.element.find("button#select_file_button").button( "option", "disabled", true );
					boxobj.element.find("button#upload_file_button").button( "option", "disabled", true );
					isHaveFile = true;	
				}
			}
			else if(boxobj.options.type == 'multiple' &&  boxobj.uploadFormObjSel.length>0 )
			{			
				boxobj.element.find("button#add_file_button").button( "option", "disabled", true );
				boxobj.element.find("button#upload_file_button").button( "option", "disabled", true );
				isHaveFile = true;
			}
			
			if(isHaveFile)
			{
				for(index in boxobj.uploadFormObjSel)
				{
					var listfile = $("div[uploadformid='"+boxobj.uploadFormObjSel[index]+"']");
					var filename = listfile.find("span:first").text();
					var html = '<div style="position: absolute;font-weight: bold;padding:2px 0px 0px 10px;"><span>';
					html += filename+'</span> <span id="progress_value" style="color:#FF4500"></span> <span id="progress_status"> [等待上传......]</span></div>';	
					listfile.html(html);
					listfile.progressbar({value:0});
				}
				
				boxobj._delay(boxobj._onUploadHandler,500);
			}
		},
		
		//构造函数
		_create: function() {
			this.oldid = this.element.attr("id");
			var d=new Date();
			var boxid = $.md5("a"+d.getTime());
			
			this.boxid = boxid;	
			this.uploadIframeObj = null;
			this.uploadFormObj = null;
			this.uploadFormObjSel = new Array();
			this.currentFormid = '';
			this.uploadStatus = new Array();
			
			this.element.attr("id",boxid)
						.addClass("ui-widget")
						.addClass("small");
						
			this.element.data("oldid",this.element.attr("id"));
			this.element.data("boxid",boxid);
			this.element.css("text-align","left");
			this.element.append('<div id="upload_file_list_box" ></div>');
			this.element.append('<div id="upload_file_select_box" ></div>');
			this.uploadIframeObj = this._createUploadIframe(boxid);
			//this.uploadIframeObj.bind("load",{boxobj:this},function(event){console.log("load")});
			if(this.options.type == "single")
			{
				this.uploadFormObj = this._createUploadForm(this);
			
				this.uploadFormObjSel.push(this.uploadFormObj.attr("id"));
				this.element.children("#upload_file_select_box")
							.append('<button id="select_file_button">选择文件</button>')
							.children("button#select_file_button")
							.button({icons:{primary: "ui-icon-folder-open"}})
							.bind('click',{boxobj:this},function(event){
								var boxobj = event.data.boxobj;								
								boxobj.uploadFormObj.children(":file").click();															
							});	
			}
			else if(this.options.type == "multiple")
			{
				this.uploadFormObj = this._createUploadForm(this);
				
				this.element.children("#upload_file_select_box")
							.append('<button id="add_file_button">添加文件</button>')
							.children("button#add_file_button")
							.button({icons:{primary: "ui-icon-plusthick"}})
							.bind('click',{boxobj:this},function(event){
								var boxobj = event.data.boxobj;								
								boxobj.uploadFormObj.children(":file").click();
							});					
			}
			this.element.children("#upload_file_select_box")
						.append('<button id="upload_file_button">上传</button>')
						.children('button#upload_file_button')
						.button({icons:{primary: "ui-icon-arrowthickstop-1-n"}})
						.bind('click',{boxobj:this},this._onStartUpload);		
			 
			
			
		},
		_destroy: function(){
			this.uploadIframeObj.remove();
			this.uploadFormObj.remove();
			for(index in this.uploadFormObjSel)
			{
				if(this.uploadFormObjSel[index] !='')
				{
					$("#"+this.uploadFormObjSel[index]).remove();
				}
			}
			this.element.children("#upload_file_list_box").remove();
			this.element.children("#upload_file_select_box").remove();
			this.element.removeClass("ui-widget").removeClass("small").attr("id",this.oldid);
		}
	
	}); 
	
 })(jQuery);
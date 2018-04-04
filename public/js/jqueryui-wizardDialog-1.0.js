/*!
 *	wizard dialog  v1.0
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
	$.widget("custom.wizardDialog", $.ui.dialog,{
		
		_initSteps: function(stepsOp){
			this.stepCount = 0;
			var steps = [];		
			
			for(index in stepsOp)
			{
				if(("title" in stepsOp[index]) && (typeof( stepsOp[index].title) == "string") 
				&& ("url" in stepsOp[index]) && (typeof( stepsOp[index].url) == "string") 
				&& ("formSelector" in stepsOp[index]) &&(typeof( stepsOp[index].formSelector) == "string"))
				{
					steps.push(stepsOp[index]);
					this.stepCount++;	
				}				
			}
			
			this.options.steps=steps;
		},
		//重载选项设置方法
		_setOption: function(key,value){
			if(key == "steps" )
			{
				this._initSteps(value)
			}
			else if(key == "buttons")
			{
				var buttons = this._createWizardButtons();
				this._super(key,buttons);
			}
			else
			{
				this._super(key,value);
			}	
				
		},
		
		//显示等待图标
		_showWaitIcon: function(){
			var html = '<div><img src ="'+this.options.loadIcon+'" style="float:left"/></div>';
			var width = this.element.width();
			var height = this.element.height();
			var obj = $(html)
			obj.css("position","absolute")
			   .css("width",width)
			   .css("height",height)
			   .prependTo(this.element);
			var imgobj = obj.find("img");    
			imgobj.bind("load",{parentWidth:width,parentHeight:height},function(event){
				var top = event.data.parentHeight/2 - $(this).height()/2;
				var left = event.data.parentWidth/2 - $(this).width()/2;
				$(this).css("margin-top",top);
				$(this).css("margin-left",left);
			});	
		},
		//步数改变
		_onStepChange: function(oper){
			var newstep = this.currentStep;
			var oldstep = this.currentStep;
			if(oper === "next" && this.currentStep < this.stepCount)
			{
				newstep = this.currentStep + 1;
			}
			else if(oper === "prev" && this.currentStep > 1)
			{
				newstep = this.currentStep - 1;
			}
			else if(oper == "cancel" )
			{
				newstep = 1;
			}
			
			var formdatastr =  "";
			var formdataarr = [];
			if(this.options.steps[oldstep-1].formSelector.length>0)
			{
				var formdatastr =  $(this.options.steps[oldstep-1].formSelector).serialize();
				var formdataarr =  $(this.options.steps[oldstep-1].formSelector).serializeArray();
			}
			var e = $.Event("stepchangebefore");	
			this._trigger('stepchangebefore',e,{oper:oper,currentStep:oldstep,newStep:newstep,formData:formdataarr,formSelector:this.options.steps[oldstep-1].formSelector});	
			
			if(this.allowChangeStep)
			{
				this.allowChangeStep = true;					
				
				var sendData = '';
				var cbData = [];
				if(oper == "next" )
				{
					this.formDataStr[oldstep] = formdatastr;
					this.formDataArr[oldstep] = formdataarr;				
					sendData = formdatastr;
				}
				else if(oper == "prev" )
				{
					var idx = newstep-1;
					if(idx>=1)
					{
						sendData = this.formDataStr[idx];
					}
					cbData = this.formDataArr[newstep];
					this.formDataStr[oldstep] = "";
					this.formDataArr[oldstep] = [];	
				}
				
				this.currentStep = newstep;
				this.oldStep = oldstep;
				this._setOption("buttons",null);

				if(oper != "complete" && this.options.steps[newstep-1].url.length>0 )
				{
					this.loadFormSelector = this.options.steps[newstep-1].formSelector;
					this.loadCbData = cbData;
			
					this.element.html("");
					this._showWaitIcon();	
					this.element.siblings("div.ui-dialog-buttonpane").find("button").button("option","disabled",true);				
					this.element.load(this.options.steps[newstep-1].url,sendData,function(){
						var wizardDlgObj = $(this).data("wizardDlgObj");
						var e = $.Event("contentload");
						wizardDlgObj.element.siblings("div.ui-dialog-buttonpane").find("button").button("option","disabled",false);
						wizardDlgObj._trigger('contentload',
						                      e,
											  {currentStep:wizardDlgObj.currentStep,
											   oldStep:wizardDlgObj.oldStep,
											   formSelector:wizardDlgObj.loadFormSelector,
											   formData:wizardDlgObj.loadCbData
											   }
											   );
											
					});
					
				}
				
				var e = $.Event("stepchangeafter");
				this._trigger('stepchangeafter',e,{oper:oper,currentStep:newstep,oldStep:oldstep,formData:cbData});	
				
			}
			

		},
		
		//下一步按键回调
		_onNextButtonClick: function(event){
			var wizardDlgObj = $(this).data("wizardDlgObj");
			wizardDlgObj._onStepChange("next");			
		},	
		//上一步按键回调
		_onPrevButtonClick: function(event){
			var wizardDlgObj = $(this).data("wizardDlgObj");
			wizardDlgObj._onStepChange("prev");						
		
		},	
		//取消按键回调
		_onCancelButtonClick: function(event){
			var wizardDlgObj = $(this).data("wizardDlgObj");
			wizardDlgObj._onStepChange("cancel");					
		
		},	
		//完成回调
		_onCompleteButtonClick: function(event){
			var wizardDlgObj = $(this).data("wizardDlgObj");
			wizardDlgObj._onStepChange("complete");	
		},			
		//创建按键
		_createWizardButtons: function(){
			var buttons = [];
			if(this.stepCount>0 && this.currentStep>0)
			{
				if(this.stepCount === 1)
				{
					buttons.push({text:"完成",click:this._onCompleteButtonClick});
				}
				else if(this.stepCount > 1)
				{
					if(this.currentStep === 1)
					{
						buttons.push({text:"下一步",click:this._onNextButtonClick});
					}
					else if(this.currentStep === this.stepCount)
					{
						buttons.push({text:"上一步",click:this._onPrevButtonClick});
						buttons.push({text:"完成",click:this._onCompleteButtonClick});
					}
					else
					{
						buttons.push({text:"上一步",click:this._onPrevButtonClick});
						buttons.push({text:"下一步",click:this._onNextButtonClick});						
					}
				}
			}
			if(this.options.haveCancel)
			{
				buttons.push({text:"取消",click:this._onCancelButtonClick});
			}
			this._setOption("title",this.options.steps[this.currentStep - 1].title);
			return buttons;
			
		},

			
		//重载构函数
		_create: function(){
			this.loadFormSelector = "";
			this.loadCbData = [];
			this.stepCount = 0;
			this.currentStep = 1;			
			
			if(!("haveCancel" in this.options))
			{
				this.options.haveCancel = false;
			}

			if(!("loadIcon" in this.options))
			{
				this.options.loadIcon = null;
			}			
			
			if(!("contentLoad" in this.options))
			{
				this.options.contentLoad = null;
			}

			if(!("stepChangeAfter" in this.options))
			{
				this.options.stepChangeAfter = null;
			}			
			
			if(!("stepChangeBefore" in this.options))
			{
				this.options.stepChangeBefore = null;
			}			
			
			if(!("steps" in this.options))
			{
				this.options.steps = [];
			}
			else
			{
				this._initSteps(this.options.steps);
			}
			this.allowChangeStep = true;
			this.formDataStr = [];
			this.formDataArr = [];			
			this._super();		
			this._setOption("buttons",null);	
			this.element.data("wizardDlgObj",this);	
			
		},
		
		//允许改变
		allowChange: function(allow){
			this.allowChangeStep = allow;
		},
		
		//重载open
		open: function(){
			this._super();
			this._onStepChange("init");
		}
		
	});
})(jQuery);
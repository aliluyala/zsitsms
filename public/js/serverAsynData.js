/*!
 *	ServerAsynData  v1.0
 *  qq:674822668
 *  email:674822668@qq.com
 *
 *           
 *  Copyright 2015 Tang DaYong.
 */

 
if(typeof(window) == "undefined")
{
	// worker
	var dataUrl = "";
	var stop = true;
	var xmlhttp;
	if (XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	function queryData(){
		if(dataUrl != "")
		{
			xmlhttp.open("GET",dataUrl+"&t="+Math.random(),false);
			xmlhttp.send();
			//console.log(xmlhttp.status);
			if(xmlhttp.status == 200 && xmlhttp.responseText.length>0)
			{
				postMessage(xmlhttp.responseText);
				//postMessage("addddd");
			}	
		}
		if(stop) return;
		setTimeout("queryData()",500);
	}

	onmessage = function(event){
		var command = event.data.command;
		if(command == "SetURL")
		{
			dataUrl = event.data.url;
		}
		else if(command == "Start")
		{
			stop = false;
			queryData();
		}
		else if(command == "stop")
		{
			stop = true;
		}
		
	};
	
	
	
} 
else
{
	(function(exports){
		var doc = exports.document,
			a = {},
			expose = +new Date(),
			rExtractUri = /((?:http|https|file):\/\/.*?\/[^:]+)(?::\d+)?:\d+/,
			isLtIE8 = ('' + doc.querySelector).indexOf('[native code]') === -1;
		exports.getCurrAbsPath = function(){
			// FF,Chrome
			if (doc.currentScript){
				return doc.currentScript.src;
			}
	
			var stack;
			try{
				a.b();
			}
			catch(e){
				stack = e.fileName || e.sourceURL || e.stack || e.stacktrace;
			}
			// IE10
			if (stack){
				var absPath = rExtractUri.exec(stack)[1];
				if (absPath){
					return absPath;
				}
			}
	
			// IE5-9
			for(var scripts = doc.scripts,
				i = scripts.length - 1,
				script; script = scripts[i--];){
				if (script.className !== expose && script.readyState === 'interactive'){
					script.className = expose;
					// if less than ie 8, must get abs path by getAttribute(src, 4)
					return isLtIE8 ? script.getAttribute('src', 4) : script.src;
				}
			}
		};
	}(window));


	//console.log(window.getCurrAbsPath());
	
	function ServerAsynData(url,cbfun)
	{
		this.url = url;
		this.workerobj = new Worker(window.getCurrAbsPath());
		this.workerobj.onmessage = function(event){
			//console.log(event.data);
			cbfun(event.data);		
		};
		this.close = function(){
			this.workerobj.postMessage({command:"Stop"});
			this.workerobj.terminate();
		};
		this.start = function(){
			this.workerobj.postMessage({command:"Start"});
		};	
		this.workerobj.postMessage({command:"SetURL",url:this.url});		
	}
	
} 

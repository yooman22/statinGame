
function LivestWebsocket( u, p ) {
	var socket;
	var url = null;
	var host= u;//'175.118.126.31';
	//var host = "betest.kr";
	//var host = '110.10.189.31';
	var port= p;//18080;
	var path=null;

	this.connect = function( param  )
	{
	    console.log("Connecting to "+host+":"+port);
	    this.init( param );
	}

	this.init = function( param ){
		var self = this;
		host="ws://"+host+":"+port;
		url=host;
		this.log('trying WebSocket - : ' + url);
		try{
			socket = new WebSocket(host);
			self.log('WebSocket - status ' + socket.readyState);

			if( param == null || param.onmessage == null || param.onmessage == undefined ){
				socket.onmessage = function(msg){
					self.log("Ws-data"+msg);
					self.log("Server>: "+msg.data);
				};
			}else{
				socket.onmessage = param.onmessage;
			}

			socket.onclose   = function(msg){
				self.log("Disconnected - status "+this.readyState);
			};

			if( param == null || param.onopen == null || param.onopen == undefined ){
				socket.onopen =function(msg){
					self.log("Welcome - status "+this.readyState);
				};
			}else{
				socket.onopen = param.onopen;
			}

	  }catch(ex){
		  self.log(ex);
	  }
	}

	this.send = function( obj ){
		var self = this;
		msg = JSON.stringify( obj );
		if(!msg){ alert("Message can not be empty"); return; }
		try{
			socket.send(msg);
			//self.log('>>: '+msg);
		}catch(ex){
			self.log(ex);
		}
	}

	this.quit = function(){
	  socket.close();
	  socket=null;
	}

	this.log = function(msg){
		console.log( msg );
	  //$("#log").append( "<br>"+msg );
	  //var textarea = document.getElementById('log');
	  //textarea.scrollTop = textarea.scrollHeight; //scroll to bottom
	  }

}

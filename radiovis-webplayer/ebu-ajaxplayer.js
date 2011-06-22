


			/*******************************
				STOMP topic of your station:
				for more information, see RadioVIS Specification at www.radiodns.org
			********************************/
			//var topic ="";
			
			
			/////////////////////////////////
			// Don't modify!!
			/////////////////////////////////
			var visserver="";
			var visport = "";
			var imglastid = "";
			var textlastid = "";
			
			//RadioDNS Resolution on topic
			function rdnslookup(t){
					//topic = t;
					var jqxhr = $.ajax({ url: "radiovis-webplayer/rdns-lookup.php", type: "GET", data: {'topic': topic} })
					.success(function(data) { 
						if(data.indexOf(":") == -1)
							$("#textframe").html("<div style='color:red'>RadioVIS server not found, wrong topic?</div><div style='color:#700;'>"+data+"</div>");
						else{
							var s = data.split(":");
							$("#textframe").html("<div style='color:#555'>Connecting to "+data+"</div>");
							startVIS(s[0], s[1]);
						}
					})
					.error(function() { })
					.complete(function() {  });
			}
			
			//Start visualisation on RadioVIS server
			function startVIS(server, port){ 
					visserver = server; 
					visport = port;
					setTimeout(function(){ sndReq("image"); }, 1000);
					setTimeout(function(){ sndReq("text"); }, 1200);
			}
			
			//Open a connection to the server and stay pending until new message
			function sndReq(type) {
					var t = topic+"/"+type;
					var jqxhr = $.ajax({ url: "radiovis-webplayer/comet.php", type: "GET", data: {'last_id': (type=="image")?imglastid:textlastid, 'topic': t, 'visserver':visserver, 'visport':visport} })
					.success(function(data) { 
					
						if(data.indexOf("@error:") !=-1)
							$("#textframe").html("<div style='color:red'>RadioVIS server problem</div><div style='color:#700;'>"+data+"</div>");
						else
							handleMsg(data, type);
					
					})
					.error(function() {  })
					.complete(function() { setTimeout(function(){ sndReq(type); }, 1000); });
			}
			

			var index = 0;
			function handleMsg(msgbrut, type){
					if(msgbrut.indexOf('SHOW') != -1 || msgbrut.indexOf('TEXT') != -1){

						var tag = "SHOW";
						if(type=="text") tag = "TEXT";
						
						var posstart = msgbrut.indexOf("\n"+tag)+tag.length+1;
						var posend = msgbrut.indexOf("\n", posend-posstart);
						
						var content = msgbrut.substr(posstart, posstart+posend).trim();
						
						tag = "message-id:";
						posstart = msgbrut.indexOf("\n"+tag)+tag.length+1;
						posend = msgbrut.indexOf("\n", posstart);
						var newid = msgbrut.substr(posstart, posend-posstart).trim();
			
						tag = "link:";
						posstart = msgbrut.indexOf("\n"+tag)+tag.length+1;
						posend = msgbrut.indexOf("\n", posstart);
						var link = msgbrut.substr(posstart, posend-posstart).trim();
			
			
						if(type == "image"){
						
							$("#I"+index).attr('src',content);
							$("#LI"+index).attr('href',link);
							$("#I"+index).load(function(){
								var ind = this.id.charAt(1);
								if(ind == "1")
									$("#I0").fadeOut(1000);
								else
									$("#I0").fadeIn(1000);
								//sndReq(type);
							});

							index = (index+1)%2;
							imglastid = newid;
							
						}
						else if(type == "text"){
						
							$("#textframe").html("<a href=\""+link+"\">"+content+"</a>");
							
							if(newid != textlastid)
								//sndReq(type);
							textlastid = newid;
							
						}
						
					}
			}
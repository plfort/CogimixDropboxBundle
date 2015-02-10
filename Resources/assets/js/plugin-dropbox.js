function dropboxPlayer(musicPlayer) {
	this.name = "Dropbox";
	this.cancelRequested=false;
	this.musicPlayer = musicPlayer;
	this.currentState = null;
	this.soundmanagerPlayer = soundManager;

	this.currentSoundObject=null;
	this.timeoutGetSong = null;
	var self = this;
	self.musicPlayer.cursor.progressbar();
	
	this.requestCancel=function(){
		self.cancelRequested=true;
		if(self.currentSoundObject){
			self.currentSoundObject.destruct();
			self.cancelRequested=false;
		}
		
	}
	
	this.play = function(item) {
		
		if(self.timeoutGetSong !==null){
			self.timeoutGetSong.clear();
		}
		
		self.timeoutGetSong = new Timer(function(){
		var postData = {path:item.pluginProperties.url};
		
		$.post(Routing.generate('_dropbox_tmp_url'),postData,function(response){
			if(response.success == true){
		self.currentSoundObject=self.soundmanagerPlayer.createSound({
			  id: item.id.toString(),
			  url: response.data.url,
			  autoLoad: true,
			  autoPlay: true,
			  multiShot : false,
			  volume: self.musicPlayer.volume,
			  onload: function() {
				  self.musicPlayer.enableControls();
				  self.musicPlayer.cursor.slider("option", "max", this.duration/1000).progressbar();			  
				  self.musicPlayer.bindCursorStop(function(value) {
					  self.currentSoundObject.setPosition(value*1000);
					});

			  },
			  onstop: function(){
				 this.destruct();
				  self.musicPlayer.cursor.slider("option", "max", 0).progressbar('value',0);
				  if(self.timeoutGetSong !==null){
						self.timeoutGetSong.clear();
					}
			  },
			  onfinish: function(){
				  this.destruct();
				  self.musicPlayer.next();
				  if(self.timeoutGetSong !==null){
						self.timeoutGetSong.clear();
					}
			  },
			  whileloading: function(){
				
				  self.musicPlayer.cursor.slider("option", "max", this.duration/1000).progressbar('value',(this.bytesLoaded/this.bytesTotal)*100 );
			  },
			  whileplaying: function(){
				 if(self.cancelRequested == false){
					  if(self.musicPlayer.cursor.data('isdragging')==false){
					  self.musicPlayer.cursor.slider("value", this.position/1000);
					  }
				 }else{
					 self.cancelRequested = false;
					 this.destruct();
				 }
			  },
			  
			  
			});
			}else{
				self.musicPlayer.next();
			}
		},'json');
		},1000);
	};
	
	this.stop = function(){
		logger.debug('call stop soundmanager');	
		if(self.currentSoundObject !=null){
			self.currentSoundObject.stop();
			if(self.timeoutGetSong !==null){
				self.timeoutGetSong.clear();
			}
		}
		
	}
	
	this.pause = function(){
		logger.debug('call pause soundmanager');
		if(self.currentSoundObject !=null){
			self.currentSoundObject.pause();
		}
		
	}
	
	this.resume = function(){
		logger.debug('call resume soundmanager');
		if(self.currentSoundObject !=null){
			self.currentSoundObject.resume();
		}
	}
	
	this.setVolume = function(value){
		logger.debug('call setvolume soundmanager');
		if(self.currentSoundObject!=null){
			self.currentSoundObject.setVolume(value);
		}
	}
	
}




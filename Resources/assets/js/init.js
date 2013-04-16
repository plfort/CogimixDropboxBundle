$("body").on('musicplayerReady',function(event){
	event.musicPlayer.addPlugin('db',new dropboxPlayer(event.musicPlayer));
});

$(document).ready(function(){
	$("#loginDropboxBtn").click(function(event){
		
		$.get(Routing.generate('_dropbox_login'),function(response){
			if(response.success == true){
				 window.open(response.data.authUrl);
			}
		},'json');
		return false;
		
	});
	
});

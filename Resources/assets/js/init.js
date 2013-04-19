$("body").on('musicplayerReady',function(event){
	event.musicPlayer.addPlugin('db',new dropboxPlayer(event.musicPlayer));
});

$(document).ready(function(){
	$("#dropbox-menu").on('click','#loginDropboxBtn',function(event){
		
		$.get(Routing.generate('_dropbox_login'),function(response){
			if(response.success == true){
				 window.open(response.data.authUrl,"login_dropbox_popup","left=300,location=false,menubar=no, status=no, scrollbars=no, menubar=no, width=800, height=600");
			}
		},'json');
		return false;
		
	});
	
	$("#dropbox-menu").on('click','#logoutDropboxBtn',function(event){
		
		$.get(Routing.generate('_dropbox_logout'),function(response){
			if(response.success == true){
				 refreshPage();
			}
		},'json');
		return false;
		
	});
	
});

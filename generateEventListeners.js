function generateEventListeners() {
    function fitPlaylistWidgetSize() {
		let
			menuWidgetHeight = menuWidget.offsetHeight,
			height = 'calc( 70vh - ' + menuWidgetHeight + 'px - 15px )';
		
		playlistWidget.style.setProperty( 'height' , height );
	}
        
     function showHideMenuWidget( evt ) {
		let
			button = evt.target,
			isHidden = menuWidget.classList.contains( 'hidden' );
		if ( isHidden ) {
			menuWidget.classList.remove( 'hidden' );
			button.innerHTML = '˄';
		}
		else {
			menuWidget.classList.add( 'hidden' );
			button.innerHTML = '˅';
		}
		fitPlaylistWidgetSize();
	}
    
	function showOptionsTab( evt ) {
		let
			button = evt.target,
			buttons = [...document.getElementsByClassName( 'tab-select-item' )],
			tab,
			tabs = [...document.getElementsByClassName( 'tab' )];
		
		if( button.classList.contains( 'selected' ) )
			return
		
		for( let i in buttons )
			buttons[ i ].classList.remove( 'selected' );
		
		button.classList.add( 'selected' );
		
		for( let i in tabs ) {
			tab = tabs[ i ];
			
			if( tab.classList.contains( 'options' ) )
				tab.classList.remove( 'hidden' );
			else
				tab.classList.add( 'hidden' );
		}
	}
	
	function showCompareTab( evt ) {
		let
			button = evt.target,
			buttons = [...document.getElementsByClassName( 'tab-select-item' )],
			tab,
			tabs = [...document.getElementsByClassName( 'tab' )];
		
		if( button.classList.contains( 'selected' ) )
			return
		
		for( let i in buttons )
			buttons[ i ].classList.remove( 'selected' );
		
		button.classList.add( 'selected' );
		
		for( let i in tabs ) {
			tab = tabs[ i ];
			
			if( tab.classList.contains( 'compare' ) )
				tab.classList.remove( 'hidden' );
			else
				tab.classList.add( 'hidden' );
		}
	}
	
	function showYtPlaylistTab( evt ) {
		let
			button = evt.target,
			buttons = [...document.getElementsByClassName( 'tab-select-item' )],
			tab,
			tabs = [...document.getElementsByClassName( 'tab' )];
		
		if( button.classList.contains( 'selected' ) )
			return
		
		for( let i in buttons )
			buttons[ i ].classList.remove( 'selected' );
		
		button.classList.add( 'selected' );
		
		for( let i in tabs ) {
			tab = tabs[ i ];
			
			if( tab.classList.contains( 'yt-playlist' ) )
				tab.classList.remove( 'hidden' );
			else
				tab.classList.add( 'hidden' );
		}
	}
	
	//Show tab buttons
	showOptionsTabButton.addEventListener( 'click' , showOptionsTab );
	showCompareTabButton.addEventListener( 'click' , showCompareTab );
	showYtPlaylistTabButton.addEventListener( 'click' , showYtPlaylistTab );
	
    //Option widget show/hide button
    fitPlaylistWidgetSize();
    showHideMenuWidgetButton.addEventListener( 'click' , showHideMenuWidget );
    
    //Playlist options
    playlistTypeForm.addEventListener( 'change' , updatePlaylist );
    playlistDateInput.addEventListener( 'change' , updatePlaylist );
    playlistChannelSelect.addEventListener( 'change' , updatePlaylist );
	compareDateInput1.addEventListener( 'change' , updatePlaylist );
	compareDateInput2.addEventListener( 'change' , updatePlaylist );
	compareChannelSelect.addEventListener( 'change' , updatePlaylist );
}
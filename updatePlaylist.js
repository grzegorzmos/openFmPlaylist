function updatePlaylist( evt ) {
	function generateYtButtonsEventListeners() {
		let buttons = [...playlistWidget.getElementsByClassName( 'play-YT' )];
		
		for( let i in buttons )
			buttons[ i ].addEventListener( 'click' , handleYtButtonClick );
	}
	
	function updateWithChartsPlaylist() {
		const
			date = playlistDateInput.value,
			channelId = playlistChannelSelect.value,
			xmlHttp = new XMLHttpRequest();
			
		xmlHttp.onreadystatechange = function() {
			if( this.readyState !== 4 ) return;
			if( this.status !== 200 ) return;
			
			playlistWidget.innerHTML = this.responseText;
			generateYtButtonsEventListeners();
		};
		
		xmlHttp.open( 'POST', 'getChartsPlaylist.php' , true );
		xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xmlHttp.send( 'date=' + date + '&channelId=' + channelId );
	}
	
	function updateWithComparePlaylist() {
		const
			date1 = compareDateInput1.value,
			date2 = compareDateInput2.value,
			channelId = compareChannelSelect.value,
			xmlHttp = new XMLHttpRequest();
		
		xmlHttp.onreadystatechange = function() {
			if( this.readyState !== 4 ) return;
			if( this.status !== 200 ) return;
			
			playlistWidget.innerHTML = this.responseText;
			generateYtButtonsEventListeners();
		};
		
		xmlHttp.open( 'POST', 'getComparePlaylist.php' , true );
		xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xmlHttp.send( 'date1=' + date1 + '&date2=' + date2 + '&channelId=' + channelId );
	}
	
	function updateWithTimetablePlaylist() {
		const
			date = playlistDateInput.value,
			channelId = playlistChannelSelect.value,
			xmlHttp = new XMLHttpRequest();
		
		xmlHttp.onreadystatechange = function() {
			if( this.readyState !== 4 ) return;
			if( this.status !== 200 ) return;
			
			playlistWidget.innerHTML = this.responseText;
			generateYtButtonsEventListeners();
		};
		
		xmlHttp.open( 'POST', 'getTimetablePlaylist.php' , true );
		xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xmlHttp.send( 'date=' + date + '&channelId=' + channelId );
	}
	
	//CONSTRUCTOR
	//Pure playlists case
	if( showOptionsTabButton.classList.contains( 'selected' ) ) {
		if( playlistChannelSelect.value === '' ) return;
		
		let
			type,
			typeOptions = [...playlistTypeForm.getElementsByTagName( 'input' )];
		
		playlistWidget.innerHTML = '';
		
		//Detecting playlist type
		for( let i in typeOptions )
			if( typeOptions[ i ].checked ) {
				type = i;
				break;
			}
		
		//Timetable case
		if( type === '0' )
			updateWithTimetablePlaylist();
		//Chartslist case
		else
			updateWithChartsPlaylist();
	}
	//Compare case
	else {
		if( compareChannelSelect.value === '' ) return;
		
		playlistWidget.innerHTML = '';
		
		updateWithComparePlaylist();
	}
}
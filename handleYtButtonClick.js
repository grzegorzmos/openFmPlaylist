function handleYtButtonClick() {
    let
		query = '',
        tr = this.parentNode.parentNode,
        trackId;
    
	function getArtistsColumnPosition() {
		tableHeads = tr.parentElement.getElementsByTagName( 'th' );
		for( let i in tableHeads )
			if( tableHeads[ i ].innerText === 'Artists' )
				return i;
		return -1;
	}
		
	function getTitleColumnPosition() {
		tableHeads = tr.parentElement.getElementsByTagName( 'th' );
		for( let i in tableHeads )
			if( tableHeads[ i ].innerText === 'Title' )
				return i;
		return -1;
	}
		
	function generateQuery() {
		if( !(tr instanceof HTMLElement) ) return
		
		let
			artistsColumnPosition,
			titleColumnPosition,
			tableHeads,
			rowCells;
		
		artistsColumnPosition = getArtistsColumnPosition();
		titleColumnPosition = getTitleColumnPosition();
		
		if( artistsColumnPosition < 0 ) return
		if( titleColumnPosition < 0 ) return
		
		rowCells = tr.getElementsByTagName( 'td' );
		
		query = '';
		query += rowCells[ titleColumnPosition ].innerText;
		query += ' ';
		query += '-';
		query += ' ';
		query += rowCells[ artistsColumnPosition ].innerText;
	}
		
	function generateIdAndAppendToYtPlaylist() {
		if( query.length < 1 ) return
		
		let	xmlHttp = new XMLHttpRequest();
		
		xmlHttp.onreadystatechange = function() {
			if ( this.readyState !== 4 ) return
			if ( this.status !== 200 ) return
			
			trackId = this.responseText;
			ytPlaylist( query , trackId );
		};
		
		xmlHttp.open( 'GET', 'getYoutubeKey.php' + '?q=' + query , true );
		xmlHttp.send();
	}
	
	//Constructor
	try {
		generateQuery();
		generateIdAndAppendToYtPlaylist();
	}
	catch( e ) {
		console.log( e );
		return
	}
}
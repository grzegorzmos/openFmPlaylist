function ytPlaylist( trackTitle , id ) {
	const tabNode = document.getElementsByClassName( 'yt-playlist-container' )[0];
	
	this.appendTrack = function( trackTitle , id ) {
		let
			node = document.createElement( 'div' ),
			playButton = document.createElement( 'button' ),
			removeButton = document.createElement( 'button' );
		
		playButton.innerHTML = '►';
		
		removeButton.innerHTML = '▬';
		
		node.classList.add( 'yt-playlist-item' );
		node.classList.add( 'in-queue' );
		
		node.appendChild( playButton );
		node.appendChild( removeButton );
		
		node.innerHTML += trackTitle;
		
		node.value = id;
		
		tabNode.appendChild( node );
		
		node.children[0].addEventListener( 'click' , play );
		node.children[1].addEventListener( 'click' , remove );
		
		this.playNextTrack();
	};
	
	this.playNextTrack = function() {
		const nextTrackNode = document.getElementsByClassName( 'in-queue' )[0];
		
		if( typeof nextTrackNode === 'undefined' ) return
		if( player.getPlayerState() > 0 && player.getPlayerState() < 4 ) return
		
		nextTrackNode.classList.remove( 'in-queue' );
		nextTrackNode.children[0].click();
	};
	
	function play() {
		const id = this.parentNode.value;
		
		if( typeof id !== 'undefined' )
			player.loadVideoById( id );
	};
	
	function remove() {
		tabNode.removeChild( this.parentNode );
	};
	
	if( typeof trackTitle === 'undefined' ) return
	if( typeof id === 'undefined' ) return
	
	this.appendTrack( trackTitle , id );
}
window.onload = initialize;

function initialize() {
	//Initialize youtube playlist
	const youtubePlaylist = new ytPlaylist();
	
	//Initialize youtube player
	player = new YT.Player( 'player' , {
		playerVars: {
			'autoplay' : 1
		},
		events: {
			'onStateChange': youtubePlaylist.playNextTrack
		}
	});
	
    generateEventListeners();
    generatePlayerBackground();
}
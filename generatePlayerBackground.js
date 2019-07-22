function generatePlayerBackground() {
	const node = document.createElement( 'div' );
	
	node.classList.add( 'player-background' );
	
	let pre;
	for( let i = 0 ; i < 50 ; i++ ) {
		pre = document.createElement( 'pre' );
		
		pre.classList.add( 'player-background-line' );
		
		pre.innerHTML = '';
		pre.innerHTML += '         '.substr( 0 , ( (3*i) % 9 ) );
		
		for( let j = 0 ; j < 50 ; j++ )
			pre.innerHTML += 'open.fm ';
		
		node.appendChild( pre );
	}
	
	document.getElementsByClassName( 'widget player' )[0].appendChild( node );
}
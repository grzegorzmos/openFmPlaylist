<?php
    include 'utilities.php';
    
    if( isset( $_POST[ 'channelId' ] , $_POST[ 'date' ]  ) )
		echo getChartsPlaylist( $_POST[ 'date' ] , $_POST[ 'channelId' ] );
    else
        echo '';
?> 
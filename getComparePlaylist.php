<?php
    include 'utilities.php';
	
    if( isset( $_POST[ 'channelId' ] , $_POST[ 'date1' ] , $_POST[ 'date2' ]  ) )
		echo getComparePlaylist( $_POST[ 'date1' ] , $_POST[ 'date2' ] , $_POST[ 'channelId' ] );
    else
        echo '';
?> 
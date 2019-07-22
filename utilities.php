<?php
    function addChannel( $id , $channel ) {
        if( !is_numeric( $id ) ) return false;
        if( !is_string ( $channel ) ) return false;
        
        $pdo = connect();
        try {
            $sql = "INSERT INTO channels ( id , name ) VALUES ( ? , ? )";
            $statement = $pdo->prepare( $sql );
            $statement->execute( [ $id , $channel ] );
            disconnect();
            return true;
        }
        catch( PDOException $e ) {
            disconnect();
            return false;
        }
    }
    
    function connect() {
        $servername = "localhost";
        $dbname = "";
        $username = "";
        $password = "";
        
        try {
            $pdoDsn = "mysql:host=$servername;dbname=$dbname;charset=UTF8";
            $pdoOptions = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            
            $pdo = new PDO( $pdoDsn , $username , $password , $pdoOptions );
            
            return $pdo;
        }
        catch(PDOException $e) {
            echo 'Connection error: ' . $e->getMessage();
            return null;
        }
    }
    
    function disconnect() {
        if( isset( $pdo ) )
            $pdo = null;
    }
    
    function generateHtmlFromSqlResult( $result , $colnames ) {
        $output = '<table>';
        
        $output .= '<tr>';
        $output .= '<th></th>';
        foreach( $colnames as $colname )
            $output .= '<th>' . $colname . '</th>';
        $output .= '</tr>';
        
        foreach( $result as $line ) {
            $output .= '<tr>';
            $output .= "<td><button class='play-YT'></button></td>";
            foreach( $colnames as $colname )
                $output .= '<td>' . $line[$colname] . '</td>';
            $output .= '</tr>';
        }
        
        $output .= '</table>';
        
        return $output;
    }
    
    function getChannelsArray() {
        $pdo = connect();
        $sql = "
            SELECT
                id,
                name
            FROM
                channels
            ORDER BY
                2";
        $query = $pdo->prepare( $sql );
        $query->execute();
        $result = $query->fetchAll();
        
        $pdo = null;
        
        return $result;
    }
    
    function getChartsPlaylist( $date , $channelId ) {
        if( !isset( $date ) ) return '';
        if( !isset( $channelId ) ) return '';
        
        $pdo = connect();
        $sql = "
                SELECT
                    ROW_NUMBER() OVER(
                        ORDER BY Σ DESC, Artists, Title ) AS '#',
                    COUNT( p.time ) AS 'Σ',
                    p.artist AS 'Artists',
                    p.title AS 'Title'
                FROM
                    playlist AS p
                    INNER JOIN channels AS ch
                        ON p.channelId = ch.id
                WHERE
                    DATE_FORMAT( p.time , '%Y-%m-%d') = ? AND ch.id = ?
                GROUP BY
                    3, 4
                ORDER BY
                    1
            ";
        $query = $pdo->prepare( $sql );
        $query->execute( [ $date , $channelId ] );
        $playlist = $query->fetchAll();
        
        $pdo = null;
        
        return generateHtmlFromSqlResult( $playlist , array( '#' , 'Σ' , 'Artists' , 'Title' ) );
    }
    
	function getComparePlaylist( $date1 , $date2 , $channelId ) {
		if( !isset( $date1 ) ) return '';
		if( !isset( $date2 ) ) return '';
        if( !isset( $channelId ) ) return '';
		
		$pdo = connect();
        $sql = "
                SELECT
					ROW_NUMBER() OVER(
                        ORDER BY Mark DESC, Artists, Title ) AS '#',
					SUM( flag ) AS Mark,
					artist AS Artists,
					title AS Title
				FROM
					( SELECT DISTINCT
						CASE
							WHEN DATE_FORMAT( time , '%Y-%m-%d' ) = ? THEN -1
							WHEN DATE_FORMAT( time , '%Y-%m-%d' ) = ? THEN 1
						END AS flag,
							title,
							artist
						FROM
							playlist
						WHERE
							channelId = ? ) AS p
				WHERE
					flag IS NOT NULL
				GROUP BY
					3, 4
				ORDER BY 1
            ";
        $query = $pdo->prepare( $sql );
        $query->execute( [ $date1 , $date2 , $channelId ] );
        $playlist = $query->fetchAll();
        
        $pdo = null;
        
        return generateHtmlFromSqlResult( $playlist , array( '#' , 'Mark' , 'Artists' , 'Title' ) );
	}
    
    function getTimetablePlaylist( $date , $channelId ) {
        if( !isset( $date ) ) return '';
        if( !isset( $channelId ) ) return '';
        
        $pdo = connect();
        $sql = "
                SELECT
                    RANK() OVER( ORDER BY p.time ) AS '#',
                    CAST( p.time AS time ) AS Time,
                    p.artist AS Artists,
                    p.title AS Title,
                    p.album AS Album,
                    p.year AS Year
                FROM
                    playlist AS p
                    INNER JOIN channels AS ch
                        ON p.channelId = ch.id
                WHERE
                    DATE_FORMAT( p.time , '%Y-%m-%d') = ? AND ch.id = ?
                ORDER BY
                    1
            ";
        $query = $pdo->prepare( $sql );
        $query->execute( [ $date , $channelId ] );
        $playlist = $query->fetchAll();
        
        $pdo = null;
        
        return generateHtmlFromSqlResult( $playlist , array( '#' , 'Time' , 'Artists' , 'Title' , 'Album' , 'Year' ) );
    }
    
    function updatePlaylist() {
        date_default_timezone_set( 'Europe/Warsaw' );
        ignore_user_abort(true);
        
        $nErrors = 0;
        $start = microtime(true);
        
        $json = file_get_contents( "https://open.fm/api/api-ext/v2/channels/long.json" );
        $json = json_decode( $json , true );
        
        //3 tries to connect to the DB
        $connectionTries = 3;
        while( $connectionTries > 0 && !isset( $pdo ) )
            $pdo = connect();
        
        if( !isset( $pdo ) ) {
            echo "ERROR: No connection!";
            return false;
        }
        
        $sql = 
            "INSERT INTO
                playlist ( channelId , artist , title , time , album , year )
            VALUES
                ( ? , ? , ? , ? , ? , ? )";
        $query = $pdo->prepare( $sql );
        
        $channels = $json['channels'];
        foreach( $channels as $channel ) {
            $id = $channel['id'];
            $tracks = $channel['tracks'];
            
            foreach( $tracks as $track ) {
                try {
                    $query->execute( [
                            $id,
                            $track[ 'song' ][ 'artist' ],
                            $track[ 'song' ][ 'title' ],
                            date( 'Y-m-d H:i:s' ,  $track[ 'begin' ] ),
                            $track[ 'song' ][ 'album' ][ 'title' ],
                            (int)$track[ 'song' ][ 'album' ][ 'year' ]
                        ] );
                }
                catch( PDOException $e ) {
                    $nErrors = $nErrors + 1;
                    //echo "\r\n" . $e . "\r\n";
                }
            }
        }
        
        $pdo = null;
        echo 'Done! Number of errors: ' . $nErrors . '.';
        echo 'Execution time: ' . ( microtime(true) - $start );
        return true;
    }
?>

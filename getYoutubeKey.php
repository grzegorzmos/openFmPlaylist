<?php
    include ( 'simple_html_dom.php' );
    
    try {
        $query = $_GET[ 'q' ];
    }
    catch ( Exception $e ) {
        $query = null;
    }
    
    function getYoutubeKey( $query ) {
        
        
        if( !isset( $query ) )
            return '';
        
        $href = 'http://youtube.com/results?search_query=' . urlencode( $query );
        
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $href);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        
        $response = curl_exec($ch);
        
        curl_close($ch);
        
        $ytDom = str_get_html( $response );
        //echo $ytDom;
        preg_match(
            "/=.+$/",
            $ytDom->find( ".item-section" )[0]->find( 'a' )[0]->href , 
            $output );
        //print_r( $output );
        return $output[0];
    }
    
    echo substr( getYoutubeKey( $query ) , 1 );
?>
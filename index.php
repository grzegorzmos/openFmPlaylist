<html>
    <head>
        <link rel='stylesheet' type='text/css' href='style.css' />
	<script src='https://www.youtube.com/player_api'></script>
        <script src='generatePlayerBackground.js'></script>
        <script src='ytPlaylist.js'></script>
        <script src='handleYtButtonClick.js'></script>
        <script src='updatePlaylist.js'></script>
        <script src='generateEventListeners.js'></script>
        <script src='initialize.js'></script>
    </head>
    <body>
        <div class='widget player'>
            <div id='player'></div>
	</div>
        <div id='menuWidget' class='widget menu'>
			
			<div class='tab-select-container'>
				<div id='showOptionsTabButton' class='tab-select-item selected'>Options</div>
				<div id='showCompareTabButton' class='tab-select-item'>Compare</div>
				<div id='showYtPlaylistTabButton' class='tab-select-item'>Yt playlist</div>
			</div>
			<div class='tab options'>
				<div class='playlistTypeOption'>
					<span>Select playlist type:</span>
					<form id='playlistTypeForm'>
						<input type='radio' name='playlistType' checked></input>
						<label>Timetable</label>
						<input type='radio' name='playlistType'></input>
						<label>Chartlist</label>
					</form>
				</div>
				<div class='playlistTypeOption'>
					<span>Select date:</span>
					<?php
						echo "<input id='playlistDateInput' type='date' min='2019-06-29' value='" . date( 'Y-m-d' ) ."'/>";
					?>
				</div>
				<div class='playlistTypeOption'>
					<span>Select channel:</span>
					<?php
						include 'utilities.php';
						
						$selectNode = '';
						
						$channels = getChannelsArray();
						
						$selectNode .= "<select id='playlistChannelSelect'>";
						$selectNode .= '<option disabled selected></option>';
						foreach( $channels as $channel ) {
							$selectNode .= '<option ';
							$selectNode .= 'value=';
							$selectNode .= "'" . $channel[ 'id' ] ."'";
							$selectNode .= '>' . $channel[ 'name' ] . '</option>';
						}
						$selectNode .= '</select>';
						
						echo $selectNode;
					?>
				</div>
			</div>
			<div class='tab compare hidden'>
				<div>
					<span>Select 1st date:</span>
					<?php
						echo "<input id='compareDateInput1' type='date' min='2019-06-29' value='" . date( 'Y-m-d' ) ."'/>";
					?>
				</div>
				<div>
					<span>Select 2nd date:</span>
					<?php
						echo "<input id='compareDateInput2' type='date' min='2019-06-29' value='" . date( 'Y-m-d' ) ."'/>";
					?>
				</div>
				<div>
					<span>Select channel:</span>
					<?php
						$selectNode = '';
						
						$channels = getChannelsArray();
						
						$selectNode .= "<select id='compareChannelSelect'>";
						$selectNode .= '<option disabled selected></option>';
						foreach( $channels as $channel ) {
							$selectNode .= '<option ';
							$selectNode .= 'value=';
							$selectNode .= "'" . $channel[ 'id' ] ."'";
							$selectNode .= '>' . $channel[ 'name' ] . '</option>';
						}
						$selectNode .= '</select>';
						
						echo $selectNode;
					?>
				</div>
			</div>
			<div class='tab yt-playlist hidden'>
				<div class='yt-playlist-container'></div>
			</div>
        </div>
        <button id='showHideMenuWidgetButton'>^</button>
        
        <div id='playlistWidget' class='widget playlist'></div>
    </body>
</html>

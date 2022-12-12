@php
    if(!isset($_GET['gameId'])) {

        $url = "g?".$_SERVER['QUERY_STRING'].'&gameId='.$game_content['origin_game_id'].'&consumerId=stakelogic';
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: $url");
    }


@endphp

<base href="https://cdn.st01-gs-stakelogic.com"> 
<?php echo $game_content['html'] ?>
<style>html{display:none;}</style>
<script>
   if (self == top) {
       document.documentElement.style.display = 'block'; 
   } else {
       top.location = self.location; 
   }
</script>
<script>
	gcw.gameElementsInit(gameEventListener);
	function gameEventListener(type, data) {
		console.log("Game event: " + type, data);
	}
</script>
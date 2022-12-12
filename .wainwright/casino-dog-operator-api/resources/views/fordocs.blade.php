<html><head>
		<meta http-equiv="expires" content="0">
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<style>
			iframe {
				max-width: 100%;
				width: 800px;
				height: 300px;
			}
		</style>
	</head>

	<body>
    
		<h1>PARENT WINDOW</h1>
		
		<h2>Automatic data send from parent to child when child window is fully loaded</h2>
		
		<iframe id="mainframe" src="child.html"></iframe>
		
		<script>
		
			/* DEFINITIONS */
            var frame = document.getElementById("mainframe");
            frame = frame ? frame.contentWindow : null;
                        
			/*
			* RECEIVE MESSAGE FROM CHILD
                    */
            window.addEventListener("message", (e) => {
                var data = e.data;

                if(data === "shakehand") {
                            console.log("SHAKEHAND RECEIVED FROM CHILD")
                            frame.postMessage("HARE AND NOW THIS TEXT IS BEING SENT TO CHILD", "*");
                }
                });
			

		</script>
	
	

</body


></html>



child html


<html><head>
		<meta http-equiv="expires" content="0">
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	</head>

	<body>
		<h1>CHILD</h1>
		
		<div>
			<h3>Received data:</h3>
			<div id="placeholder">shakehand</div>
		</div>
		
		<script>
		
			/* DEFINITIONS */
      var parentWindow = window.parent;
			
			/*
			* RECEIVE MESSAGE FROM PARENT
			*/

      window.addEventListener("message", (e) => {
        var data = e.data;
				console.log("RECEIVED message from PARENT TO CHILD", data);

        document.getElementById("placeholder").innerText = data;

      });
			

			/* SHAKE HAND WITH PARENT */
      window.addEventListener("load", () => {
				parentWindow.postMessage("shakehand", "*");
      });
			
			
			
		</script>
		
	
	

</body></html>
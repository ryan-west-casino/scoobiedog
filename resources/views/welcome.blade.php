<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <link rel="icon" type="image/x-icon" href="https://i.ibb.co/hfLLWjZ/ezgif-1-62518d2ae2.png"> 
        <link href="https://fonts.bunny.net/css?family=space-grotesk:300,400,500,600,700" rel="stylesheet" />
        <style>
            *{
            transition: all 0.6s;
            }

            html {
                height: 100%;
                background-color: white;
            }

            body{
                font-family: 'Space Grotesk', sans-serif;
                color: black;
                margin: 0;
            }

            #main{
                display: table;
                width: 100%;
                height: 100vh;
                text-align: center;
            }

            .fof{
                display: table-cell;
                vertical-align: middle;
            }

            .fof h1{
                font-size: 50px;
                display: inline-block;
                padding-right: 12px;
                animation: type .5s alternate infinite;
            }

            .fof h2{
                font-size: 30px;
                display: inline-block;
                padding-right: 12px;
                animation: type .5s alternate infinite;
            }

            @keyframes type{
                from{box-shadow: inset -3px 0px 0px black;}
                to{box-shadow: inset -3px 0px 0px transparent;}
            }

            button {
                display: inline-block;
                outline: 0;
                border: 0;
                cursor: pointer;
                background: #000000;
                color: #FFFFFF;
                border-radius: 8px;
                padding: 14px 24px 16px;
                font-size: 16px;
                font-weight: 700;
                line-height: 1;
                transition: transform 200ms,background 200ms;
                &:hover {
                    transform: translateY(-2px);
                }
            }
        </style>

    </head>
    <body>
    <div id="main">
    	<div class="fof">
                <p><h3><b>
		<iframe src="https://www.veed.io/embed/55edf15c-9f8f-4669-8577-448df2893341" width="644" height="414" frameborder="0" title="back panel" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </b></h3></p>
                <p><a href="/api/playground/gameslist"><button onclick="#">Go to Playground</button></p></a>
    	</div>
    </div>
    </body>
</html>


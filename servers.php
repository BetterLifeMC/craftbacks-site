<?php
    include('lib/sql.php');
    $sqlquery = new doSQL();
    $sqlquery->doSQLStuff("SELECT * FROM `Servers` WHERE fingerprint ='".$_GET['fingerprint']."'");
    $names = $sqlquery->get_names();
    $ports = $sqlquery->get_ports();
    $fingerprints = $sqlquery->get_fingerprints();
    $hostnames = $sqlquery->get_hostnames();
    $id = $sqlquery->get_id();
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <title>craftback</title>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script>
            // Snatched from https://happycoding.io/tutorials/java-server/post#polling-with-ajax
            function getChats(){
                $.get({
                    url: 'http://<?php echo $hostnames[0]; ?>:<?php echo $ports[0]; ?>/getLog',
                    dataType: 'text',
                    type: 'GET',
                    async: true,
                    statusCode: {
                        404: function (response) {
                            alert(404);
                        },
                        200: function (response) {
                            document.getElementById("logConsole").innerHTML = response;
                        }
                    },
                    error: function (jqXHR, status, errorThrown) {
                    }
                });
                setTimeout(getChats, 1000);
            }
            $(function () {
                $('form').on('submit', function (e) {
                    e.preventDefault();
                    $.ajax({
                        type: 'get',
                        url: 'http://<?php echo $hostnames[0]; ?>:<?php echo $ports[0]; ?>/sendMessage/',
                        data: $('form').serialize(),
                        success: function () {
            		        document.getElementById('messageBox').value="";
                        }
                    });
                });
                document.getElementById('messageBox').value="";
             });

            var playerUUIDList;
            var playerNameList;
            var currentPlayerCount;
            function getPlayerInfo(){
            	$.get( "http://games01-serv:8080/getPlayerUUIDS", function( data ) {
            	    playerUUIDList = data;
            	});
            	$.get( "http://games01-serv:8080/getPlayerNames", function( data ) {
            	    playerNameList = data;
            	});

            	playerUUIDList = playerUUIDList.replace("]","");
            	playerUUIDList = playerUUIDList.replace("[","");
            	playerUUIDList.replace("\n","");
            	playerArrayUUIDList = this.playerUUIDList.split(", ");

            	playerNameList = playerNameList.replace("]","");
            	playerNameList = playerNameList.replace("[","");
            	playerNameList.replace("\n","");
            	playerArrayNameList = this.playerNameList.split(", ");

            	currentPlayerCount = playerArrayList.length;
            }
        </script>
    </head>
    <body onload="getChats();">
        <!-- Shamelessly snatched from W3 -->
        <div>
            <div class="topnav" id="myTopnav">
                <a href="/craftback-site" class="active">Home</a>
            </div>
            <pre>
                <div class="logConsole" id="logConsole"></div>
            </pre>
            <div class="messageBox">
                <form>
                    <input id="messageBox" class="messageBox" type="text" name="message" />
                </form>
            </div>
        </div>
    </body>
</html>

<?php

session_start();

if (isset($_SESSION["user_id"])) {
    // Include the database configuration
    require __DIR__ . "/dbconfig/database.php"; // Adjust the path as needed
    $sql = "SELECT * FROM user WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Search Student - CarPool Management</title>
    <meta charset="UTF-8">
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">-->
    <link rel="stylesheet" href="css/dark.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
    <noscript>
        <p style="text-align: center;">Please enable JavaScript in your browser before using this website.</p>
    </noscript>

    <center>
        <h1><a href="index.php">Search Student - CarPool</a></h1>
        <!--<a href="index.php"><img src="img/txlogo.png" alt="Thanksgiving Elementary" ></a>-->

        <?php if (isset($user)) : ?>

            <p>Hello, Welcome : <?= htmlspecialchars($user["name"]) ?></p>
            <br>

            <!--code for QR code scanner-->

            <!-- <script src="js/html5-qrcode.min.js"></script>

            <div style="width: 300px" id="reader"></div>-->

            <!--code for QR code scanner-->

            <script>
                var key_map = {};

                function search_student(search) {

                    //console.log(search);

                    if (search in key_map)
                        return;

                    key_map[search] = true;

                    setTimeout(function() {
                        delete key_map[search];
                    }, 2000);
                    $.ajax({
                        url: 'search-action.php',
                        method: 'post',
                        data: {
                            query: search
                        },
                        success: function(response) {
                            $("#table-container").html(response);
                        }
                    });


                }


                // QR code scanner *****************************************************************************************
                /*  function onScanSuccess(decodedText, decodedResult) {
                     // Handle on success condition with the decoded text or result.
                     console.log(`Scan result: ${decodedText}`, decodedResult);
                     document.getElementById("search").value = decodedText;
                     search_student(decodedText);
                     playSound();


                 }

                 function onScanError(errorMessage) {
                     // handle on error condition, with error message
                     console.log(errorMessage);
                 }

                 var html5QrcodeScanner = new Html5QrcodeScanner(
                     "reader", {
                         fps: 2,
                         qrbox: 200
                     });
                 html5QrcodeScanner.render(onScanSuccess, onScanError); */
                // QR code scanner *****************************************************************************************    
            </script>


            <script type="text/javascript">
                // Fix iOS Audio Context by Blake Kus https://gist.github.com/kus/3f01d60569eeadefe3a1
                // MIT license
                (function() {
                    window.AudioContext = window.AudioContext || window.webkitAudioContext;
                    if (window.AudioContext) {
                        window.audioContext = new window.AudioContext();
                    }
                    var fixAudioContext = function(e) {
                        if (window.audioContext) {
                            // Create empty buffer
                            var buffer = window.audioContext.createBuffer(1, 1, 22050);
                            var source = window.audioContext.createBufferSource();
                            source.buffer = buffer;
                            // Connect to output (speakers)
                            source.connect(window.audioContext.destination);
                            // Play sound
                            if (source.start) {
                                source.start(0);
                            } else if (source.play) {
                                source.play(0);
                            } else if (source.noteOn) {
                                source.noteOn(0);
                            }
                        }
                        // Remove events
                        document.removeEventListener('touchstart', fixAudioContext);
                        document.removeEventListener('touchend', fixAudioContext);
                    };
                    // iOS 6-8
                    document.addEventListener('touchstart', fixAudioContext);
                    // iOS 9
                    document.addEventListener('touchend', fixAudioContext);

                    document.addEventListener('touchend', () => window.audioContext.resume());
                })();

                var $status = document.querySelector('#status');

                function playSound() {
                    var path = 'sound/scanned.mp3';
                    var context = window.audioContext;
                    var request = new XMLHttpRequest();
                    //$status.innerHTML = 'Playing ' + path;
                    request.open('GET', path, true);
                    request.responseType = 'arraybuffer';
                    request.addEventListener('load', function(e) {
                        context.decodeAudioData(this.response, function(buffer) {
                            var source = context.createBufferSource();
                            source.buffer = buffer;
                            source.connect(context.destination);
                            source.start(0);
                        });
                    }, false);
                    request.send();
                }

                setTimeout(playSound, 3000);
            </script>
            <br>

            <!-- Function to only search numbers on search bar -->
            <script>
                function isNumberKey(evt) {
                    var charCode = (evt.which) ? evt.which : event.keyCode
                    if (charCode > 31 && (charCode < 48 || charCode > 57))
                        return false;
                    return true;
                }
            </script>

            <script type="text/javascript">
                $(document).ready(function() {

                    $("#searchForm").submit(function(e) {
                        e.preventDefault()
                        var search = $("#search").val();
                        search_student(search);

                        // Clear the input field after searching
                        $("#search").val(""); // This line clears the input field after search
                    });

                });
            </script>

            <!-- Function to play sound on windows desktop / does not work on ios for web -->
            <!--<script>

            function playSound(url) {
            const audio = new Audio(url);
            audio.play();
            }

        </script>-->

            <div class="container">
                <form method="POST" id="searchForm">
                    <input type="text" placeholder="Student ID" id="search" name="search" onkeypress="return isNumberKey(event)" style="width: 250px; height: 40px; font-size: 20px; padding: 8px;">

                    <!--<button name="submit">Search</button>-->

                </form>

                <br>
                <div class="container" id="table-container">
                    <table class="table" id="table-data">
                        <thead>
                            <tr>
                                <th><strong>Student ID</strong></th>
                                <th><strong>First Name</strong></th>
                                <th><strong>Last Name</strong></th>
                                <th><strong>Grade</strong></strig>
                                </th>
                                <th><strong>Teacher</strong></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <p><a href="logout.php">Log out</a></p>
                <!--<input type="button" value="Log out" onClick="document.location.href='logout.php'" />-->

            <?php else : ?>

                <p><a href="login.php">Log in</a> <!--or <a href="signup.html">Sign up</a>--></p>

            <?php endif; ?>

    </center>
</body>

<footer>
    <p><?php include "includes/footer.php"; ?></p>
</footer>

</html>
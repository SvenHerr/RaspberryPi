<!DOCTYPE HTML>
<html>

<?php 
// With php we habe an option to hide
$api_url = 'https://api.herrmannsven.de/pimonitor/getall/?token=123';

// Read JSON file
$json_data = file_get_contents($api_url);

// Decode JSON data into PHP array
$response_data = json_decode($json_data);
?>

<head>
    <script>
        var myJson = "";
        var myJsonTest = [];
        var totalCount = 0;
        var count = 0;
        var counter = 2;

        var date = new Date();
        var dd = String(date.getDate()).padStart(2, '0');
        var mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = date.getFullYear();
        var currentDate = yyyy + "-" + mm + "-" + dd;

        function userAction() {
            
            myJson =  <?php echo json_encode($response_data );?>;
            
            myJson = myJson.filter(function(x) {
                return x.piId == 1;
            });
           
            // do something with myJson
            console.log("vor if" + currentDate);
        };

        function setCurrentDateString() {
            dd = String(date.getDate()).padStart(2, '0');
            mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
            yyyy = date.getFullYear();
            currentDate = yyyy + "-" + mm + "-" + dd;
        }

        function setPreviousDay() {
            date.setDate(date.getDate() - 1);
            setCurrentDateString();
            setArray();
        }

        function isNextButtonAllowed() {
            var dateTemp = new Date();
            dateTempdd = String(dateTemp.getDate()).padStart(2, '0');
            dateTempmm = String(dateTemp.getMonth() + 1).padStart(2, '0'); //January is 0!
            dateTempyyyy = dateTemp.getFullYear();
            today = dateTempyyyy + "-" + dateTempmm + "-" + dateTempdd;

            if (currentDate == today) {
                return false;
            }

            return true;
        }

        function setNextDay() {
            if (isNextButtonAllowed() == false) {
                return;
            }
            date.setDate(date.getDate() + 1);
            setCurrentDateString();
            setArray();
        };

        function resetDate() {
            date = new Date();
            setCurrentDateString();
            date = new Date(currentDate);
            setArray();
            console.log(date);
        };

        function setArray() {
            console.log("CurrentDate: " + currentDate);
            setLabelDate();

            myJsonTest = [];
            myJson.forEach(element => {

                if (element.createDate.includes(currentDate)) {
                    console.log(element.createDate);
                    if (element.cpuTemperature != 0 & element.cpuTemperature != 123) {
                        myJsonTest.push({
                            x: count,
                            y: element.cpuTemperature
                        });
                        count += 1;
                    }
                }
                totalCount += 1;
            });
            console.log("found: " + myJsonTest.length + " data. From: " + totalCount);

            doChartStuff();

            calculateAverage();
            setHighestTemperatur();
            setLowestTemperatur();
        }

        function setLabelDate() {
            var label = document.getElementById("labelDate");
            label.innerHTML = currentDate;
        }

        function doChartStuff() {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Magic Mirror Temperatur"
                },
                data: [{
                    type: "line",
                    indexLabelFontSize: 16,
                    dataPoints: myJsonTest
                }]
            });
            chart.render();
        }

        function calculateAverage() {

            var sum = 0;
            for (var i = 0; i < myJsonTest.length; i++) {
                sum += parseInt(myJsonTest[i].y, 10); //don't forget to add the base
            }

            var avg = sum / myJsonTest.length;

            document.getElementById("averageTemp").innerHTML = (Math.round(avg * 100) / 100) + "°C";
            console.log(avg);
        }

        function setHighestTemperatur() {

                var temphighestTemperatur = 0;
                myJsonTest.forEach(element =>{
                if(temphighestTemperatur < element.y)
                    temphighestTemperatur = element.y
                });
                document.getElementById("highestTemp").innerHTML = temphighestTemperatur + "°C";
                console.log(temphighestTemperatur);
        }

        function setLowestTemperatur() {

            var tempLowestTemperatur = myJsonTest[0].y;
                myJsonTest.forEach(element =>{
                if(tempLowestTemperatur > element.y)
                    tempLowestTemperatur = element.y
                });
                document.getElementById("lowestTemp").innerHTML = tempLowestTemperatur + "°C";
                console.log(tempLowestTemperatur);
        }

        async function init() {
            await userAction();
            setArray();
            setLabelDate();
            doChartStuff();
        }

        function callTime() {

            var i = 1;

            function myLoop() {
                setTimeout(function() {

                    document.getElementById("waitlabel").innerHTML = "( " + counter + " )"
                    counter -= 1;
                    i++;
                    if (i < 5) {
                        myLoop();
                    }
                }, 1000)
            }
            myLoop();
        }

        function hideWaitLabel() {
            document.getElementById("waitLabelContainer").style.display = 'none';
        }

        window.onload = async function() {

            callTime();
            await init();
            hideWaitLabel();
        }
    </script>
    <style>
        .container {
            margin-top: 50px;
            justify-content: center;
            display: flex;
        }
        
        .center {
            vertical-align: middle;
            text-align: center;
        }
        
        .margin {
            margin: 25px;
        }
        
        .marginSmall {
            margin: 15px;
        }
        
        .padding {
            padding: 10px;
        }
        
        .waitStyle {
            color: brown;
        }
    </style>
</head>

<body>

    <div id="chartContainer" style="height: 300px; width: 95%;" class="padding"></div>
    <div class="center" id="waitLabelContainer">
        <h2 class="waitStyle">Please wait <label id="waitlabel">( 3 )</label></h2>
    </div>

    <div class="container margin">
        <div>
            <div class="center margin">
                <div class="padding">
                    <label>Avg. Temp:</label>
                    <label id="averageTemp">XX.XX°C</label>
                </div>
                <div class="padding">
                    <label>Highest Temp:</label>
                    <label id="highestTemp">XX.XX°C</label>
                </div>
                <div class="padding">
                    <label>Lowest Temp:</label>
                    <label id="lowestTemp">XX.XX°C</label>
                </div>
                <div>
                    <label id="labelDate">Date</label>
                </div>
            </div>
            <div>
                <button class="marginSmall" onclick="setPreviousDay()">Previous</button>
                <button class="marginSmall" onclick="resetDate()">Reset</button>
                <button class="marginSmall" onclick="setNextDay()">Next</button>
            </div>
        </div>

    </div>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>

</html>
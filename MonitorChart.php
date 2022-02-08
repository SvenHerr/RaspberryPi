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
        var responseDayArray = [];
        var totalCount = 0;
        var count = 0;
        var counter = 2;
        var responseArray = [];

        var date = new Date();
        var dd = String(date.getDate()).padStart(2, '0');
        var mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = date.getFullYear();
        var currentDate = yyyy + "-" + mm + "-" + dd;

        function getApiResponse() {
            
            myJson =  <?php echo json_encode($response_data );?>;      
            myJson = myJson.filter(function(x) {
                return x.piId == 1;
            });
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
        };

        function alltimeHelper(){
            responseArray = [];
            myJson.forEach(element => {
                    if (element.cpuTemperature != 0 & element.cpuTemperature != 123) {
                        responseArray.push({
                            x: count,
                            y: element.cpuTemperature
                        });
                        count += 1;
                    }
            });
        }

        function setArray() {
            
            setLabelDate();

            responseDayArray = [];
            myJson.forEach(element => {
                if (element.createDate.includes(currentDate)) {
                    if (element.cpuTemperature != 0 & element.cpuTemperature != 123) {
                        responseDayArray.push({
                            x: count,
                            y: element.cpuTemperature
                        });
                        count += 1;
                    }
                }
                totalCount += 1;
            });
            console.log("found: " + responseDayArray.length + " data. From: " + totalCount);

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
                    dataPoints: responseDayArray
                }]
            });
            chart.render();
        }

        function calculateAverage() {

            var sum = 0;
            for (var i = 0; i < responseDayArray.length; i++) {
                sum += parseInt(responseDayArray[i].y, 10); //don't forget to add the base
            }

            var avg = sum / responseDayArray.length;
            document.getElementById("averageTemp").innerHTML = (Math.round(avg * 100) / 100) + "°C";
        }

        function calculateAlltimeAverage() {

            var sum = 0;
            for (var i = 0; i < responseArray.length; i++) {
                sum += parseInt(responseArray[i].y, 10); //don't forget to add the base
            }

            var avg = sum / responseArray.length;
            document.getElementById("alltimeAverageTemp").innerHTML = (Math.round(avg * 100) / 100) + "°C";
        }

        function setHighestTemperatur() {

            var temphighestTemperatur = 0;
            responseDayArray.forEach(element =>{
                if(temphighestTemperatur < element.y)
                    temphighestTemperatur = element.y
                });
            document.getElementById("highestTemp").innerHTML = temphighestTemperatur + "°C";
        }

        function setLowestTemperatur() {

            var tempLowestTemperatur = responseDayArray[0].y;
            responseDayArray.forEach(element =>{
                if(tempLowestTemperatur > element.y)
                    tempLowestTemperatur = element.y
                });
            document.getElementById("lowestTemp").innerHTML = tempLowestTemperatur + "°C";
        }

        function setAlltimeLowTemperatur() {

            var tempLowestTemperatur = responseArray[0].y;
            responseArray.forEach(element =>{
                if(tempLowestTemperatur > element.y)
                    tempLowestTemperatur = element.y
                });
            document.getElementById("alltimeLowTemp").innerHTML = tempLowestTemperatur + "°C";
        }

        function setAlltimeHighTemperatur() {

            var temphighestTemperatur = 0;
            responseArray.forEach(element =>{
            if(temphighestTemperatur < element.y)
                temphighestTemperatur = element.y
            });
            document.getElementById("alltimeHighTemp").innerHTML = temphighestTemperatur + "°C";
        }

        function init() {
            getApiResponse();
            alltimeHelper();
            setArray();
            setAlltimeHighTemperatur();
            setAlltimeLowTemperatur();
            calculateAlltimeAverage();
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

        .chartContainer{
            height: 300px; 
            width: 95%;
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

        .displayFlex{
            display: flex;
        }
    </style>
</head>

<body>

    <div id="chartContainer" class="chartContainer padding"></div>
    <div class="center" id="waitLabelContainer">
        <h2 class="waitStyle">Please wait <label id="waitlabel">( 3 )</label></h2>
    </div>

    <div class="container margin">
        <div>
            <div class="displayFlex">
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
                   
                </div>
                <div class="center margin">
                    <div class="padding">
                        <label>Avg. Temp:</label>
                        <label id="alltimeAverageTemp">XX.XX°C</label>
                    </div>
                    <div class="padding">
                        <label>Highest Temp:</label>
                        <label id="alltimeHighTemp">XX.XX°C</label>
                    </div>
                    <div class="padding">
                        <label>Lowest Temp:</label>
                        <label id="alltimeLowTemp">XX.XX°C</label>
                    </div>
                </div>
            </div>

            <div  class="center">
                <label id="labelDate">Date</label>
            </div>
            
            <div class="center margin">
                <button class="marginSmall" onclick="setPreviousDay()">Previous</button>
                <button class="marginSmall" onclick="resetDate()">Reset</button>
                <button class="marginSmall" onclick="setNextDay()">Next</button>
            </div>
        </div>

    </div>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>

</html>
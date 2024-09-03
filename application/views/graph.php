<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C3.js Graphs Example</title>
    
    <!-- C3.js CSS via CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.css">
    
    <!-- D3.js via CDN (required by C3.js) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.16.0/d3.min.js"></script>
    
    <!-- C3.js JavaScript via CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.js"></script>
    
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/c3js.css">
    <style>
        /* Additional styling for the container and table */
        .container {
            width: 80%;
            margin: auto;
            padding-top: 20px;
        }
        #responseTable {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 60px;
        }

        #responseTable th, #responseTable td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #responseTable th {
            background-color: #f2f2f2;
        }

        #studentTable {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 60px;
        }

        #studentTable th, #studentTable td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #studentTable th {
            background-color: #f2f2f2;
        }
        .container {
    display: flex;
    flex-direction: column;
    align-items: center; /* Center the chart and table */
}

#collegeChart {
    width: 60%; 
    height: 380px; 
    margin-bottom: 20px; 
}

    </style>

</head>
<body>

    <div class="container">
        <!-- Line Chart -->
        <div id="lineChart" class="chart"></div>
    </div>

    <div class="container">
    <div id="barChart"></div>
    <div id="pieChart"></div>
</div>

    <div class="container">
        <div id="collegeChart"></div>
        <table id="studentTable" style="display:none;">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Department</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>


    <div class="container">
    <!-- Bar Chart for Sports Responses -->
    <div id="sportsChart" class="chart"></div>
</div>

<!-- Table for Detailed Responses -->
<div class="container">
    <table id="responseTable" style="display:none;">
        <thead>
        <tr>
            <th>Respondent</th>
            <th>Response</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>


    <script>
        // Initialize the charts with an additional dataset 'data3'
       var lineChart = c3.generate({
            bindto: '#lineChart',
            data: {
                x: 'x',
                columns: [
                    ['x', '2012-12-29', '2012-12-30', '2012-12-31'],
                    ['data1', 230, 300, 330],
                    ['data2', 190, 230, 200],
                    ['data3', 90, 130, 180],
                ],
                type: 'line'
            },
            axis: {
                x: {
                    type: 'timeseries',
                    tick: {
                        format: '%m/%d',
                    }
                }
            }
        });

        // Additional flow characteristics for lineChart
        setTimeout(function () {
            lineChart.flow({
                columns: [
                    ['x', '2013-01-11', '2013-01-21'],
                    ['data1', 500, 200],
                    ['data2', 100, 300],
                    ['data3', 200, 120],
                ],
                duration: 1100,
                done: function () {
                    lineChart.flow({
                        columns: [
                            ['x', '2013-02-11', '2013-02-12', '2013-02-13', '2013-02-14'],
                            ['data1', 200, 300, 100, 250],
                            ['data2', 100, 90, 40, 120],
                            ['data3', 100, 100, 300, 500]
                        ],
                        length: 0,
                        duration: 1100,
                        done: function () {
                            lineChart.flow({
                                columns: [
                                    ['x', '2013-03-01', '2013-03-02'],
                                    ['data1', 200, 300],
                                    ['data2', 150, 250],
                                    ['data3', 100, 100]
                                ],
                                length: 2,
                                duration: 1100,
                                done: function () {
                                    lineChart.flow({
                                        columns: [
                                            ['x', '2013-03-21', '2013-04-01'],
                                            ['data1', 500, 200],
                                            ['data2', 100, 150],
                                            ['data3', 200, 400]
                                        ],
                                        to: '2013-03-01',
                                        duration: 1100,
                                    });
                                }
                            });
                        }
                    });
                },
            });
        }, 1000);


// Generate the bar chart
var barChart = c3.generate({
    bindto: '#barChart',
    data: {
        columns: [
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 130, 100, 140, 200, 150, 50],
            ['data3', 90, 120, 160, 220, 180, 90] 
        ],
        type: 'bar',
        onclick: function (d, element) {
            // Get the index of the selected bar
            var index = d.index;

            // Fetch data for the selected index across all categories
            var pieData = [
                ['data1', barChart.data.shown()[0].values[index].value],
                ['data2', barChart.data.shown()[1].values[index].value],
                ['data3', barChart.data.shown()[2].values[index].value]
            ];
            
            // Generate the pie chart using the collected data
            generatePieChart(pieData);
        }
    }
});

// Function to generate the pie chart
function generatePieChart(pieData) {
    // Remove any existing pie chart (if needed)
    d3.select("#pieChart").select("svg").remove();

    // Create a new pie chart below the bar chart
    var pieChart = c3.generate({
        bindto: '#pieChart',
        data: {
            columns: pieData,
            type: 'pie'
        }
    });
}

//Pie chart
        var collegeData = {
            "NITK": [
                { "name": "Dinesh", "department": "Electronics Engineering" },
                { "name": "Torun", "department": "Electrical Engineering" },
            ],
            "BITS Goa": [
                { "name": "Yash", "department": "Mechanical Engineering" },
                { "name": "Subbu", "department": "Computer Science" },
                { "name": "Joseph", "department": "Mechanical Engineering" }
                
            ],
            "KLE": [
                { "name": "Pramod", "department": "Computer Science" },
                { "name": "Sanjana", "department": "Computer Science" },
                { "name": "Toufiq", "department": "Computer Science" }
            ]
        };

        // Process collegeData to dynamically generate the chart data
        var chartData = [];
        for (var college in collegeData) {
            if (collegeData.hasOwnProperty(college)) {
                chartData.push([college, collegeData[college].length]);
            }
        }

        var collegeChart = c3.generate({
            bindto: '#collegeChart',
            data: {
                columns: chartData,
                type: 'pie',
                onclick: function (d, element) {
                    showStudentTable(d);
                }
            }
        });

        // Function to display student table
        function showStudentTable(data) {
            var college = data.id;
            var studentList = collegeData[college];

            if (studentList && studentList.length > 0) {
                var tableBody = document.querySelector('#studentTable tbody');
                tableBody.innerHTML = ''; // Clear existing table data

                studentList.forEach(function (student) {
                    var row = document.createElement('tr');
                    var nameCell = document.createElement('td');
                    nameCell.textContent = student.name;
                    var departmentCell = document.createElement('td');
                    departmentCell.textContent = student.department;

                    row.appendChild(nameCell);
                    row.appendChild(departmentCell);
                    tableBody.appendChild(row);
                });

                document.getElementById('studentTable').style.display = 'table';
            } else {
                console.error("No data found for college: " + college);
                document.getElementById('studentTable').style.display = 'none';
            }
        }

    //separate use case*******************************************************************************
    var responseData = {
    "Football": {
        "Yes": [
            { "name": "Asutosh", "response": "Yes" },
            { "name": "Torun", "response": "Yes" },
            { "name": "Suraj", "response": "Yes" },
        ],
        "No": [
            { "name": "Abhinav", "response": "No" },
            { "name": "Pramod", "response": "No" }
        ],
        "Maybe": [
            { "name": "Dinesh", "response": "Maybe" },
            { "name": "Karthik", "response": "Maybe" }
        ]
    },
    "Cricket": {
        "Yes": [
            { "name": "Torun", "response": "Yes" },
            { "name": "Dinesh", "response": "Yes" }
        ],
        "No": [
            { "name": "Grace", "response": "No" },
            { "name": "Sanjana", "response": "No" }
        ],
        "Maybe": [
            { "name": "Pramod", "response": "Maybe" }
        ]
    },
    "Basketball": {
        "Yes": [
            { "name": "Ankush", "response": "Yes" },
            { "name": "Torun", "response": "Yes" },
            { "name": "Abhinav", "response": "Yes" }
        ],
        "No": [
            { "name": "Dinesh", "response": "No" }
        ],
        "Maybe": [
            { "name": "Suraj", "response": "Maybe" },
            { "name": "Asutosh", "response": "Maybe" }
        ]
    }
};

// Process responseData to dynamically generate the chart data
var chartData = [];
for (var sport in responseData) {
    if (responseData.hasOwnProperty(sport)) {
        var sportData = responseData[sport];
        var yesCount = sportData.Yes ? sportData.Yes.length : 0;
        var noCount = sportData.No ? sportData.No.length : 0;
        var maybeCount = sportData.Maybe ? sportData.Maybe.length : 0;
        
        chartData.push({
            sport: sport,
            Yes: yesCount,
            No: noCount,
            Maybe: maybeCount
        });
    }
}

var sportsChart = c3.generate({
    bindto: '#sportsChart',
    data: {
        json: chartData,
        keys: {
            x: 'sport', 
            value: ['Yes', 'No', 'Maybe']
        },
        type: 'bar',
        onclick: function (d, element) {
            showDetailedTable(d);
        }
    },
    axis: {
        x: {
            type: 'category',
            label: 'Sports'
        },
        y: {
            label: 'Responses'
        }
    }
});


    // Function to display detailed response table
    function showDetailedTable(data) {
        var sport = sportsChart.categories()[data.index]; // Get the sport based on the clicked index
        var response = data.name; // Get the response (Yes, No, Maybe)

        var responseList = responseData[sport][response];

        if (responseList && responseList.length > 0) {
            var tableBody = document.querySelector('#responseTable tbody');
            tableBody.innerHTML = ''; // Clear existing table data

            responseList.forEach(function (item) {
                var row = document.createElement('tr');
                var nameCell = document.createElement('td');
                nameCell.textContent = item.name;
                var responseCell = document.createElement('td');
                responseCell.textContent = item.response;

                row.appendChild(nameCell);
                row.appendChild(responseCell);
                tableBody.appendChild(row);
            });

            document.getElementById('responseTable').style.display = 'table';
        } else {
            console.error("No data found for sport: " + sport + ", response: " + response);
            document.getElementById('responseTable').style.display = 'none';
        }
    }
        
    </script>
    
</body>
</html>

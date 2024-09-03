<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Student Distribution</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.16.0/d3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.js"></script>
    <style>
        /* Additional styling for the container and table */
        .container {
            width: 80%;
            margin: auto;
            padding-top: 20px;
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
    </style>
</head>
<body>
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

    <script>
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
    </script>
</body>
</html>

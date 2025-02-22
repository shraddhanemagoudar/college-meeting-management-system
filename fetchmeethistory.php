<?php
require_once('includes/db_connect.php');

function getMeetingHistory() {
    global $pdo;
    try {
        $stmt = $pdo->query('
            SELECT 
                m.id as meeting_id, m.agenda, m.date, m.time, m.venue,
                s.id as staff_id, s.username, s.email, s.department,
                mh.attended, mh.outcome
            FROM meeting_history mh
            JOIN meetings m ON mh.meeting_id = m.id
            JOIN staff s ON mh.staff_id = s.id
            ORDER BY m.date DESC, m.time DESC
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting History Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #D3CAC3;
        }
        .container {
            background-color: #4C342F;
            color: #D3CAC3;
            padding: 20px;
            border-radius: 10px;
            margin-top: 50px;
        }
        .btn-custom {
            background-color: #65463E;
            color: #D3CAC3;
        }
        .btn-custom:hover {
            background-color: #B9968D;
        }
        .alert-info {
            background-color: #65463E;
            color: #D3CAC3;
            border-color: #B9968D;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Meeting History</h2>
        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="meeting_id">Meeting ID:</label>
                <input type="text" class="form-control" id="meeting_id" name="meeting_id" required>
            </div>
            <div class="form-group">
                <label for="staff_id">Staff ID:</label>
                <input type="text" class="form-control" id="staff_id" name="staff_id" required>
            </div>
            <div class="form-group">
                <label for="attended">Attended:</label>
                <select class="form-control" id="attended" name="attended" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="outcome">Outcome:</label>
                <textarea class="form-control" id="outcome" name="outcome" required></textarea>
            </div>
            <button type="submit" class="btn btn-custom">Submit</button>
        </form>

        <h2 class="mt-5">Meeting History</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Meeting ID</th>
                    <th>Agenda</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Venue</th>
                    <th>Staff ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Attended</th>
                    <th>Outcome</th>
                </tr>
            </thead>
            <tbody id="meetingHistoryBody">
                <!-- Data will be inserted here by JavaScript -->
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('get_meeting_history.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('meetingHistoryBody');
                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${row.meeting_id}</td>
                            <td>${row.agenda}</td>
                            <td>${row.date}</td>
                            <td>${row.time}</td>
                            <td>${row.venue}</td>
                            <td>${row.staff_id}</td>
                            <td>${row.username}</td>
                            <td>${row.email}</td>
                            <td>${row.department}</td>
                            <td>${row.attended ? 'Yes' : 'No'}</td>
                            <td>${row.outcome}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                });
        });
    </script>
</body>
</html>

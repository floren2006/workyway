<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> | KursusOnline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Test Database Connection</h1>
        
        <?php if($success): ?>
        <div class="alert alert-success mt-4">
            <h4>✓ Database Connected Successfully!</h4>
            <p>All queries are working correctly.</p>
        </div>
        
        <div class="card mt-4">
            <div class="card-body">
                <h3>Database Statistics:</h3>
                <ul class="list-group">
                    <li class="list-group-item">Total Courses: <?php echo $total_courses; ?></li>
                    <li class="list-group-item">Total Students: <?php echo $total_students; ?></li>
                </ul>
            </div>
        </div>
        
        <?php else: ?>
        <div class="alert alert-danger mt-4">
            <h4>✗ Database Error!</h4>
            <p>Error: <?php echo htmlspecialchars($error_message); ?></p>
        </div>
        <?php endif; ?>
        
        <div class="text-center mt-4">
            <a href="<?php echo base_url('landing'); ?>" class="btn btn-primary">Go to Landing Page</a>
            <a href="<?php echo base_url('landing/test_db'); ?>" class="btn btn-secondary">Full Database Test</a>
        </div>
    </div>
</body>
</html>
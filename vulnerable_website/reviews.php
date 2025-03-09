<?php
//check if user is authentified
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id'])) 
    $connection = 0;
else
    $connection = 1;

include "db.php"; 

if ($connection) {    
    include 'navbar.php';

    $user_id = $_SESSION['id'];

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rating = intval($_POST['rating']);
        $comment = $_POST['comment'];

        // Use prepared statement to avoid SQL syntax errors
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, rating, comment) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $rating, $comment]);

        // Refresh page to show the updated feedback table
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch all feedbacks, sorted by date (newest first)
$stmt = $conn->query("SELECT r.*, u.username 
                        FROM reviews r
                        JOIN users u ON r.user_id = u.id
                        ORDER BY r.created_at DESC");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Management</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .username-column {
            width: 20%; 
        }

        .rating-column {
            width: 10%; 
        }

        .comment-column {
            width: 50%; 
            word-wrap: break-word; /*wrap*/
        }

        .date-column {
            width: 20%; 
        }

        textarea {
            width: 100%;
            resize: vertical; 
        }
        /* comment box in Add review */
        #comment {
            width: 50%; 
            resize: vertical;
        }
        .user_id-column {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reviews</h2>

        <!-- Display Reviews in a Table -->
        <table>
            <thead>
                <tr>
                    <th class="user_id-column">User_id</th>
                    <th class="username-column">Username</th>
                    <th class="rating-column">Rating</th>
                    <th class="comment-column">Review</th>
                    <th class="date-column">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td class="user_id-column"><?php echo ($review['user_id']); ?></td>
                        <td class="username-column"><?php echo ($review['username']); ?></td>
                        <td class="rating-column"><?php echo str_repeat('⭐', $review['rating']); ?></td>
                        <td class="comment-column"><?php echo ($review['comment']); ?></td>
                        <td class="date-column"><?php echo ($review['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
   
            <hr>
        <?php if ($connection): ?>
            <!-- Add New Review Form -->
            <form action="" method="POST">
                <h3>Add Your Review</h3>
                <label for="rating">Rating (1-5):</label>
                <select name="rating" id="rating" required>
                    <option value="1">1 ⭐</option>
                    <option value="2">2 ⭐⭐</option>
                    <option value="3">3 ⭐⭐⭐</option>
                    <option value="4">4 ⭐⭐⭐⭐</option>
                    <option value="5">5 ⭐⭐⭐⭐⭐</option>
                </select>

                <br><br>

                <label for="comment">Review:</label><br>
                <textarea name="comment" id="comment" rows="4" placeholder="Write your review here..."></textarea>


                <br><br>

                <button type="submit">Submit Review</button>
            </form>
            <?php else: ?>
                <p>You must be logged in to add a new post.</p>
            <?php endif; ?>    
        </div>
    </body>
    </html>

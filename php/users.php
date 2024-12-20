<?php

// User Management Tool using WP-CLI

if (isset($_POST['destroy'])) {
    // Get the current file path
    $filePath = __FILE__;
    $lockFilePath = dirname(__FILE__) . '/ip_lock.lock';

    echo "
    <style>
        body { font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        #message-container { text-align: center; background-color: #fff; padding: 40px; border-radius: 8px; box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1); max-width: 400px; }
        .success-icon { font-size: 50px; color: #28a745; margin-bottom: 15px; }
        h2 { color: #333; font-size: 22px; margin: 0; }
        p { color: #666; font-size: 16px; margin-top: 5px; }
    </style>
    <div id='message-container'>";

    // Display success icon and friendly message
    echo "<div class='success-icon'>✓</div>";

    // Delete the ip_lock.lock file
    if (file_exists($lockFilePath) && unlink($lockFilePath)) {
        echo "<h2>Cleanup Successful</h2>";
        echo "<p>The lock file was removed successfully.</p>";
    } else {
        echo "<h2>Cleanup Partial</h2>";
        echo "<p>The lock file was not found or couldn't be removed.</p>";
    }

    // Delete the file itself
    if (unlink($filePath)) {
        echo "<h2>Tool Deleted</h2>";
        echo "<p>This tool has been removed from the system.</p>";
    } else {
        echo "<h2>Deletion Failed</h2>";
        echo "<p>There was an issue deleting this tool.</p>";
    }

    echo "</div>";

    // Stop further execution
    exit;
}

// Define the lock file path
$lockFilePath = __DIR__ . '/ip_lock.lock';

// Get the visitor's IP address
$visitorIp = $_SERVER['REMOTE_ADDR'];

// Check if the lock file exists
if (!file_exists($lockFilePath)) {
    // If not, create it and store the current visitor's IP
    file_put_contents($lockFilePath, $visitorIp);
}

// Read the locked IP from the file
$lockedIp = file_get_contents($lockFilePath);

// Check if the current visitor's IP matches the locked IP
if (trim($lockedIp) !== $visitorIp) {
    // Deny access for other IPs
    exit("Access denied. This tool is locked to another IP.");
}


// Function to list users with WP-CLI
function list_wp_users() {
    $output = shell_exec('wp user list --format=json 2>&1');
    return json_decode($output, true);
}

// Function to create a new user
function create_wp_user($username, $email, $role = 'subscriber') {
    $username = escapeshellarg($username);
    $email = escapeshellarg($email);
    $role = escapeshellarg($role);
    $output = shell_exec("wp user create $username $email --role=$role 2>&1");
    return $output;
}

// Function to reset a user's password
function reset_wp_user_password($user_id, $new_password) {
    $user_id = escapeshellarg($user_id);
    $new_password = escapeshellarg($new_password);
    $output = shell_exec("wp user update $user_id --user_pass=$new_password 2>&1");
    return $output;
}

// Function to update user details
function update_wp_user($user_id, $field, $value) {
    $user_id = escapeshellarg($user_id);
    $field = escapeshellarg($field);
    $value = escapeshellarg($value);
    $output = shell_exec("wp user update $user_id --$field=$value 2>&1");
    return $output;
}

// Function to delete a user
function delete_wp_user($user_id) {
    $user_id = escapeshellarg($user_id);
    $output = shell_exec("wp user delete $user_id --yes 2>&1");
    return $output;
}

$message = '';
$message_class = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'create':
            $username = $_POST['username'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $output = create_wp_user($username, $email, $role);
            if (strpos($output, 'Success') !== false) {
                $message_class = 'success-message';
            } else {
                $message_class = 'error-message';
            }
            $message = '<div class="' . $message_class . '">' . htmlspecialchars($output) . '</div>';
            break;
        case 'update':
            $user_id = $_POST['user_id'];
            $field = $_POST['field'];
            $value = $_POST['value'];
            $output = update_wp_user($user_id, $field, $value);
            if (strpos($output, 'Success') !== false) {
                $message_class = 'success-message';
            } else {
                $message_class = 'error-message';
            }
            $message = '<div class="' . $message_class . '">' . htmlspecialchars($output) . '</div>';
            break;
        case 'delete':
            $user_id = $_POST['user_id'];
            $output = delete_wp_user($user_id);
            if (strpos($output, 'Success') !== false) {
                $message_class = 'success-message';
            } else {
                $message_class = 'error-message';
            }
            $message = '<div class="' . $message_class . '">' . htmlspecialchars($output) . '</div>';
            break;
        case 'reset_password':
            $user_id = $_POST['user_id'];
            $new_password = $_POST['new_password'];
            $output = reset_wp_user_password($user_id, $new_password);
            if (strpos($output, 'Success') !== false) {
                $message_class = 'success-message';
            } else {
                $message_class = 'error-message';
            }
            $message = '<div class="' . $message_class . '">' . htmlspecialchars($output) . '</div>';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WP User Management</title>
    

<style>
    body { font-family: Arial, sans-serif; background-color: #f7f7f7; text-align: center; }
    #container { max-width: 600px; margin: 50px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
    h1 { font-size: 24px; color: #333; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
    th { background-color: #f0f0f0; font-weight: bold; }
    .action-buttons { display: flex; justify-content: center; gap: 5px; }
    button { padding: 8px 12px; font-size: 14px; cursor: pointer; border: none; border-radius: 5px; }
    .create-button { background-color: #28a745; color: #fff; }
    .update-button { background-color: #007bff; color: #fff; }
    .delete-button { background-color: #dc3545; color: #fff; }
    button:disabled { background-color: #ccc; cursor: not-allowed; }
    form { margin-bottom: 20px; }
    input[type="text"], input[type="email"], input[type="password"], select { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 5px; }
    
        .success-message {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 10px;
        margin: 20px 0;
        border-radius: 5px;
        text-align: center;
    }

    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 10px;
        margin: 20px 0;
        border-radius: 5px;
        text-align: center;
    }
    
            /* Accordion styles */
        .accordion { width: 100%; }
        .accordion-item { margin: 10px 0; }
        .accordion-header { background-color: #f0f0f0; color: black; padding: 10px; cursor: pointer; border-radius: 5px; text-align: left; }
        .accordion-content { display: none; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9; }
        
</style>

</head>
<body>
    <div id="container">
        <h1>WordPress User Management</h1>
                 <?php if (!empty($message)): ?>
            <?php echo $message; ?>
        <?php endif; ?>
        <!-- Display User List -->
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php foreach (list_wp_users() as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['ID']); ?></td>
                    <td><?php echo htmlspecialchars($user['user_login']); ?></td>
                    <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                    <td><?php echo htmlspecialchars($user['roles'][0]); ?></td>
                    <td>
                        <div class="action-buttons">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['ID']); ?>">
                                <button type="submit" class="delete-button" onclick="return confirm('Delete this user?');">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Accordion Structure -->
        <div class="accordion">
            
            <!-- Create User Section -->
            <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">Create New User</div>
                <div class="accordion-content">
                    <form method="POST">
                        <input type="hidden" name="action" value="create">
                        <label>Username: <input type="text" name="username" required></label><br>
                        <label>Email: <input type="email" name="email" required></label><br>
                        <label>Role: 
                            <select name="role">
                                <option value="subscriber">Subscriber</option>
                                <option value="editor">Editor</option>
                                <option value="author">Author</option>
                                <option value="administrator">Administrator</option>
                            </select>
                        </label><br>
                        <button type="submit" class="create-button">Create User</button>
                    </form>
                </div>
            </div>
            
            <!-- Update User Section -->
            <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">Update User</div>
                <div class="accordion-content">
                    <form method="POST">
                        <input type="hidden" name="action" value="update">
                        <label>User ID: <input type="text" name="user_id" required></label><br>
                        <label>Field to Update: 
                            <select name="field">
                                <option value="user_login">Username</option>
                                <option value="user_email">Email</option>
                                <option value="role">Role</option>
                            </select>
                        </label><br>
                        <label>New Value: <input type="text" name="value" required></label><br>
                        <button type="submit" class="update-button">Update User</button>
                    </form>
                </div>
            </div>
            
            <!-- Reset Password Section -->
            <div class="accordion-item">
                <div class="accordion-header" onclick="toggleAccordion(this)">Reset User Password</div>
                <div class="accordion-content">
                    <form method="POST">
                        <input type="hidden" name="action" value="reset_password">
                        <label>User ID: <input type="text" name="user_id" required></label><br>
                        <label>New Password: <input type="password" name="new_password" required></label><br>
                        <button type="submit" class="update-button">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<form method="post">
    <button type="submit" name="destroy" onclick="return confirm('Are you sure? This action is irreversible.')">
        Finished? Destroy tool now
    </button>
</form>


    <script>
        function toggleAccordion(header) {
            const content = header.nextElementSibling;
            content.style.display = content.style.display === "none" || content.style.display === "" ? "block" : "none";
        }
    </script>
</body>
</html>

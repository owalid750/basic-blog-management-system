<?php

$pageTitle = 'Manage';
include 'header.php'; // Include the header
if (!isset($_SESSION["user_id"])) {
    header("location:index.php");
    exit;
}
include 'config/config.php';
include 'classes/Database.php';
include 'classes/User.php';

$database = new Database();
$db = $database->connect();
$user = new User($db);
$currentUserRole = $user->getUserRole($_SESSION['user_id'])['role'];
//test
// echo $role['role'];
// print_r($allUsers);
// print_r($_SESSION);

?>
<style>
    /* Container for Access Denied Message */
    .access-denied-container {
        max-width: 600px;
        margin: 100px auto;
        padding: 40px;
        text-align: center;
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
    }

    .access-denied-container h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .access-denied-container p {
        font-size: 18px;
        margin-bottom: 30px;
    }

    .access-denied-container .btn-back {
        padding: 10px 20px;
        background-color: #333;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
    }

    .access-denied-container .btn-back:hover {
        background-color: #555;
    }

    .manage-users-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .manage-users-container h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .users-table {
        width: 100%;
        overflow-x: auto;
    }

    .users-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table th,
    .users-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .users-table th {
        background-color: #333;
        color: #fff;
    }

    .users-table td {
        background-color: #f9f9f9;
    }

    .users-table .btn {
        font-weight: bold;
        padding: 8px 12px;
        margin: 0 5px;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
    }


    .users-table .btn-edit {
        background-color: #333333;
        color: #ffffff;
    }

    .users-table .btn-edit:hover {
        cursor: pointer;
        background-color: #007bff;
    }

    .users-table .btn-delete {
        background-color: #333333;
        color: #ffffff;
    }

    .users-table .btn-delete:hover {
        background-color: red;
    }

    .users-table .btn-view {
        background-color: #333333;
        color: #ffffff;
    }

    .users-table .btn-view:hover {
        background-color: green;
    }

    /* Modal Styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border-radius: 8px;
        width: 80%;
        max-width: 500px;
        position: relative;
    }

    .modal-content h2 {
        margin-top: 0;
    }

    .close {
        color: #aaa;
        position: absolute;
        right: 20px;
        top: 15px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
    }

    .btn-save {
        background-color: #333333;
        color: #ffffff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-save:hover {
        background-color: #555555;
    }

    /* Success Message */
    .success-msg {
        color: #28a745;
        background-color: #dff0d8;
        border: 1px solid #d4edda;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    /* error_msg */
    .error_msg {
        color: #dc3545;
        /* Red text color */
        background-color: #f8d7da;
        /* Light red background */
        border: 1px solid #f5c6cb;
        /* Slightly darker red border */
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    /* Style for the welcome message */
    .welcome-message {
        background-color: #f4f4f4;
        /* Light grey background */
        border: 1px solid #ddd;
        /* Soft border */
        padding: 20px;
        /* Space inside the box */
        border-radius: 8px;
        /* Rounded corners */
        max-width: 400px;
        /* Width of the box */
        margin: 20px auto;
        /* Center the box */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        /* Subtle shadow for depth */
        text-align: center;
        /* Center the text */
    }

    .welcome-message h2 {
        font-size: 24px;
        /* Larger font for the heading */
        color: #333;
        /* Dark grey color */
        margin-bottom: 10px;
        /* Space below the heading */
    }

    .welcome-message h2 span {
        color: #007bff;
        /* Blue color for the username */
    }

    .welcome-message p {
        font-size: 16px;
        /* Normal font size for the paragraph */
        color: #555;
        /* Slightly lighter grey color */
        margin: 0;
        /* Remove margin */
    }

    .welcome-message .role {
        font-weight: bold;
        /* Make the role bold */
        color: #28a745;
        /* Green color for the role */
    }

    /* Default styling for roles */
    .role {
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 5px;
        text-align: center;
    }

    /* Admin role color */
    .role.admin {
        background-color: #5b4e4e;
        /* Gold background */
        color: #ffffff;
        /* Dark text color */
    }

    /* Superadmin role color */
    .role.superadmin {
        background-color: #333333;
        /* Blue background */
        color: #ffffff;
        /* White text color */
    }

    /* User role color */


    /* Additional roles can be added here */
    /* Default styling for status */
    .status {
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 5px;
        text-align: center;
    }

    /* Active status color */
    .status.active {
        /* background-color: #28a745; */
        /* Green background */
        color: #28a745;
        /* White text color */
    }

    /* Inactive status color */
    .status.inactive {

        color: #ffc107;
        /* Dark text color */
    }

    /* Deleted status color */
    .status.deleted {
        background-color: #dc3545;
        /* Red background */
        color: #ffffff;
        /* White text color */
    }


    @media (max-width: 768px) {

        .users-table th,
        .users-table td {
            padding: 10px 8px;
            font-size: 14px;
        }

        .users-table .btn {
            padding: 6px 10px;
            font-size: 12px;
        }
    }
</style>
<?php
// Check if the user is an admin or superadmin
if ($currentUserRole !== 'admin' && $currentUserRole !== 'superAdmin' && $currentUserRole !== 'sysAdmin') {
    echo '<div class="access-denied-container">
            <h1>Access Denied</h1>
            <p>This page is only accessible to administrators and superadministrators.</p>
            <a href="index.php" class="btn btn-back">Go Back to Home</a>
          </div>';
    exit;
}
?>
<?php $allUsers = $user->getAllUsersExceptCurrent($_SESSION['user_id'], $currentUserRole);
?>
<div class="manage-users-container">
    <?php if (isset($_SESSION['success_msg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['success_msg'];
                                unset($_SESSION['success_msg']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_msg'])): ?>
        <p class="error_msg"><?php echo $_SESSION['error_msg'];
                                unset($_SESSION['error_msg']); ?></p>
    <?php endif; ?>
    <div class="welcome-message">
        <h2>Welcome, <span><?php echo $_SESSION['user_name']; ?></span></h2>
        <p>Your role: <span class="role"><?php echo $currentUserRole; ?></span></p>
    </div>
    <h1>Manage Users</h1>
    <div class="users-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allUsers as $user) : ?>
                    <tr>
                        <td><?= $user['id']; ?></td>
                        <td><?= htmlspecialchars($user['username']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td class="role <?= strtolower(htmlspecialchars($user['role'])); ?>">
                            <?= htmlspecialchars($user['role']); ?>
                        </td>
                        <td class="status <?= strtolower(htmlspecialchars($user['status'])); ?>">
                            <?= htmlspecialchars($user['status']); ?>
                        </td>

                        <td>
                            <button class="btn btn-edit" onclick="openEditModal(<?= $user['id']; ?>, '<?= htmlspecialchars($user['username']); ?>', '<?= htmlspecialchars($user['email']); ?>', '<?= htmlspecialchars($user['role']); ?>', '<?= htmlspecialchars($user['status']); ?>')">Edit</button>
                            <a href="public_profile.php?id=<?= $user['id']; ?>" class="btn btn-view">View</a>
                            <?php if ($currentUserRole === 'superAdmin' || $currentUserRole === 'sysAdmin'): ?>
                                <a href="handle_manage.php?action=delete&id=<?= $user['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this user This will delete all comment adn post created by this user?');">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Edit User Modal -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit User</h2>
        <form id="editUserForm" method="post" action="handle_manage.php?action=edit">
            <input type="hidden" name="user_id" id="editUserId">
            <div class="form-group">
                <label for="editUsername">Username:</label>
                <input type="text" name="username" id="editUsername" required>
            </div>
            <div class="form-group">
                <label for="editEmail">Email:</label>
                <input type="email" name="email" id="editEmail" required>
            </div>
            <?php if ($currentUserRole === "sysAdmin") :
            ?>
                <div class="form-group">
                    <label for="editRole">Role:</label>
                    <select name="role" id="editRole">
                        <option value="superAdmin">SuperAdmin</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="editStatus">Status:</label>
                <select name="status" id="editStatus">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-save">Save Changes</button>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, username, email, role, status) {
        document.getElementById('editUserId').value = id;
        document.getElementById('editUsername').value = username;
        document.getElementById('editEmail').value = email;

        // Check if the editRole element exists before setting its value
        const editRoleElement = document.getElementById('editRole');
        if (editRoleElement) {
            editRoleElement.value = role;
        }

        document.getElementById('editStatus').value = status;

        document.getElementById('editUserModal').style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('editUserModal').style.display = 'none';
    }
</script>
<?php
require_once 'functions.php';

// add task
if(isset($_POST["add_task_button"])){
	if(!empty($_POST["task-name"])){
		$task_name = trim($_POST["task-name"]);
		addTask($task_name);
	}
}

// delete task
if (isset($_POST["delete_task_id"])) {
    $task_id = $_POST["delete_task_id"];
    deleteTask($task_id);
}

// update task status
if (isset($_POST["task_id"]) && isset($_POST["completed"])) {
	$task_id = $_POST["task_id"];
	$is_completed = $_POST["completed"] === '1';
	markTaskAsCompleted($task_id, $is_completed);
}

// subscribe email
if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	$email = trim($_POST['email']);
	subscribeEmail($email);
}

// TODO: Implement the task scheduler, email form and logic for email registration.

// In HTML, you can add desired wrapper `<div>` elements or other elements to style the page. Just ensure that the following elements retain their provided IDs.

$tasks = getAllTasks();
?>
<!DOCTYPE html>
<html>

<head>
	<!-- Implement Header !-->
	<title>Task Manager and Reminder</title>
	<link rel="stylesheet" href="./style.css">
	<meta charset="UTF-8">
</head>

<body>
	<h1>Task Manager and Reminder</h1>
	<!-- Add Task Form -->
	<form method="POST" action="">
		<!-- Implement Form !-->
		<input type="text" name="task-name" id="task-name" placeholder="Enter new task" required>
		<button type="submit" id="add-task" name="add_task_button" value="yes">Add Task</button>
	</form>

	<!-- Tasks List -->
	<ul id="tasks-list">
		<!-- Implement Tasks List (Your task item must have below
		provided elements you can modify there position, wrap them
		in another container, or add styles but they must contain
		specified classnames and input type )!-->
		<?php foreach($tasks as $task): ?>
			<li class="task-item <?= $task['completed'] ? 'completed' : '' ?>">

				<form method="POST" style="display:inline;">
					<input type="hidden" name="task_id" value="<?= $task['id'] ?>">
					<input type="hidden" name="completed" value="<?= $task['completed'] ? '0' : '1' ?>">
					<input type="checkbox" class="task-status" <?= $task['completed'] ? 'checked' : '' ?> onchange="this.form.submit()">
				</form>

				<span class="task-name"><?= htmlspecialchars($task['name']) ?></span>

				<form method="POST" style="display:inline;">
					<input type="hidden" name="delete_task_id" value="<?= $task['id'] ?>">
					<button type="submit" class="delete-task">Delete</button>
				</form>
			</li>
		<?php endforeach; ?>
	</ul>

	<!-- Subscription Form -->
	<form method="POST" action="">
		<!-- Implement Form !-->
		<input type="email" name="email" placeholder="Enter your email" required>
		<button type="submit" id="submit-email">Subscribe</button>
	</form>

</body>

</html>
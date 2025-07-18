<?php
/**
 * Adds a new task to the task list
 * 
 * @param string $task_name The name of the task to add.
 * @return bool True on success, false on failure.
 */
function addTask( string $task_name ): bool {
	$file  = __DIR__ . '/tasks.txt';
	// TODO: Implement this function
	$tasks = getAllTasks();
	foreach ($tasks as $task) {
		if ($task['name'] === $task_name) {
			return false; // Task already exists
		}
	}
	$task_id = "T" . rand();
	$new_task = [
		'id' => $task_id,
		'name' => $task_name,
		'completed' => false
	];
	array_push($tasks, $new_task);
	$json = json_encode($tasks, JSON_PRETTY_PRINT);
	file_put_contents($file, $json);
	return true; // Task added successfully
}

/**
 * Retrieves all tasks from the tasks.txt file
 * 
 * @return array Array of tasks. -- Format [ id, name, completed ]
 */
function getAllTasks(): array {
	$file = __DIR__ . '/tasks.txt';
	// TODO: Implement this function
	$json = file_get_contents($file);
    $tasks = json_decode($json, true);
    return $tasks;
}

/**
 * Marks a task as completed or uncompleted
 * 
 * @param string  $task_id The ID of the task to mark.
 * @param bool $is_completed True to mark as completed, false to mark as uncompleted.
 * @return bool True on success, false on failure
 */
function markTaskAsCompleted( string $task_id, bool $is_completed ): bool {
	$file  = __DIR__ . '/tasks.txt';
	// TODO: Implement this function
	$tasks = getAllTasks();
	foreach ($tasks as &$task) {
		if ($task['id'] === $task_id) {
			$task['completed'] = $is_completed;
			$json = json_encode($tasks, JSON_PRETTY_PRINT);
			file_put_contents($file, $json);
			return true; // Task marked successfully
		}
	}
	return false; // Task not found
}

/**
 * Deletes a task from the task list
 * 
 * @param string $task_id The ID of the task to delete.
 * @return bool True on success, false on failure.
 */
function deleteTask( string $task_id ): bool {
	$file  = __DIR__ . '/tasks.txt';
	// TODO: Implement this function
	$tasks = getAllTasks();
	$length = count($tasks);
	$found = false;
	for ($i = 0; $i < $length; $i++) {
		if($tasks[$i]['id'] === $task_id) {
			unset($tasks[$i]);
			$found = true;
			break;
		}
	}
	if(!$found) return false;
	$tasks = array_values($tasks);
	$json = json_encode($tasks, JSON_PRETTY_PRINT);
	file_put_contents($file, $json);
	return true; // Task deleted successfully
}

/**
 * Generates a 6-digit verification code
 * 
 * @return string The generated verification code.
 */
function generateVerificationCode(): string {
	// TODO: Implement this function
	return rand(100000, 999999);
}

/**
 * Subscribe an email address to task notifications.
 *
 * Generates a verification code, stores the pending subscription,
 * and sends a verification email to the subscriber.
 *
 * @param string $email The email address to subscribe.
 * @return bool True if verification email sent successfully, false otherwise.
 */
function subscribeEmail( string $email ): bool {
	$file = __DIR__ . '/pending_subscriptions.txt';
	// TODO: Implement this function

	$pending_subscription_emails = json_decode(file_get_contents($file), true);

	foreach ($pending_subscription_emails as $pending_email => $data) {
		if ($pending_email === $email) {
			return false; // Email already pending
		}
	}

	$code = generateVerificationCode();
	$timestamp = time();
	$pending_subscription_emails[$email] = [
		'code' => $code,
		'timestamp' => $timestamp
	];

	file_put_contents($file, json_encode($pending_subscription_emails, JSON_PRETTY_PRINT));

	$verification_link = "http://localhost/Notifico/src/verify.php?email=" . urlencode($email) . "&code=" . urlencode($code);

	$subject = "Verify subscription to Task Planner";
	$body = '
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="UTF-8">
		<style>
			body { font-family: Arial, sans-serif; color: #333; }
			.container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
			a.button {
				display: inline-block;
				background-color: #4CAF50;
				color: white;
				padding: 10px 20px;
				text-decoration: none;
				border-radius: 5px;
				margin-top: 20px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<h2>Confirm Your Subscription</h2>
			<p>Hello,</p>
			<p>Thank you for subscribing to <strong>Task Planner</strong>. Please click the button below to verify your email address and activate your subscription.</p>
			<p><a id="verification-link" class="button" href="' . $verification_link . '">Verify Subscription</a></p>
			<hr>
			<p style="font-size: 12px; color: #888;">You are receiving this email because you subscribed to task updates on Task Planner.</p>
		</div>
	</body>
	</html>
';
	$headers = "From: no-reply@example.com\r\nContent-Type: text/html; charset=UTF-8\r\n";

	return mail($email, $subject, $body, $headers);
}

/**
 * Verifies an email subscription
 * 
 * @param string $email The email address to verify.
 * @param string $code The verification code.
 * @return bool True on success, false on failure.
 */
function verifySubscription( string $email, string $code ): bool {
	$pending_file     = __DIR__ . '/pending_subscriptions.txt';
	$subscribers_file = __DIR__ . '/subscribers.txt';
	// TODO: Implement this function

	$pending_subscription_emails = json_decode(file_get_contents($pending_file), true);

	if (!isset($pending_subscription_emails[$email]) || $pending_subscription_emails[$email]['code'] !== $code) {
		return false; // Invalid email or code
	}

	unset($pending_subscription_emails[$email]);
	file_put_contents($pending_file, json_encode($pending_subscription_emails, JSON_PRETTY_PRINT));

	$subscribers = json_decode(file_get_contents($subscribers_file), true);

	if( !is_array($subscribers)) {
		$subscribers = [];
	}
	if(!in_array($email, $subscribers)) {
		$subscribers[] = $email; // Add email to subscribers
		file_put_contents($subscribers_file, json_encode($subscribers, JSON_PRETTY_PRINT));
	}

	return true;
}

/**
 * Unsubscribes an email from the subscribers list
 * 
 * @param string $email The email address to unsubscribe.
 * @return bool True on success, false on failure.
 */
function unsubscribeEmail( string $email ): bool {
	$subscribers_file = __DIR__ . '/subscribers.txt';
	// TODO: Implement this function

	$subscribers = json_decode(file_get_contents($subscribers_file), true);

	if (!is_array($subscribers) || !in_array($email, $subscribers)) {
		return false; // Email not found in subscribers
	}

	unset($subscribers[array_search($email, $subscribers)]);
	$subscribers = array_values($subscribers); // Re-index the array
	file_put_contents($subscribers_file, json_encode($subscribers, JSON_PRETTY_PRINT));
	return true; // Unsubscribed successfully
}

/**
 * Sends task reminders to all subscribers
 * Internally calls  sendTaskEmail() for each subscriber
 */
function sendTaskReminders(): void {
	$subscribers_file = __DIR__ . '/subscribers.txt';
	// TODO: Implement this function
	$tasks = getAllTasks();
	$subscribers = json_decode(file_get_contents($subscribers_file), true);

	foreach ($subscribers as $subscriber) {
		sendTaskEmail($subscriber, array_filter($tasks, function($task) {
			return !$task['completed']; // Only send pending tasks
		}));
	}
}

/**
 * Sends a task reminder email to a subscriber with pending tasks.
 *
 * @param string $email The email address of the subscriber.
 * @param array $pending_tasks Array of pending tasks to include in the email.
 * @return bool True if email was sent successfully, false otherwise.
 */
function sendTaskEmail( string $email, array $pending_tasks ): bool {
	$subject = 'Task Planner - Pending Tasks Reminder';
	// TODO: Implement this function
	if(empty($pending_tasks)) {
		return false; // No pending tasks to send
	}
	$body = '<h1>Here are your pending tasks:</h1><ul>';
	foreach ($pending_tasks as $task) {
		$body .= '<li>' . htmlspecialchars($task['name']) . '</li>';
	}
	$body = '
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="UTF-8">
			<style>
				body { font-family: Arial, sans-serif; color: #333; }
				.container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
				ul { padding-left: 20px; }
				li { margin-bottom: 6px; }
				.unsubscribe {
					font-size: 12px;
					color: #888;
					margin-top: 30px;
				}
				a.button {
					display: inline-block;
					margin-top: 20px;
					background-color: #f44336;
					color: white;
					padding: 8px 14px;
					text-decoration: none;
					border-radius: 4px;
					font-size: 14px;
				}
			</style>
		</head>
		<body>
			<div class="container">
				<h2>⏰ Task Planner - Pending Tasks</h2>
				<p>Hi there,</p>
				<p>You have the following pending tasks:</p>
				<ul>';
	foreach ($pending_tasks as $task) {
		$body .= '<li>' . htmlspecialchars($task['name']) . '</li>';
	}
	$body .= '
				</ul>
				<p>Please complete them at your convenience.</p>
				<hr>
				<div class="unsubscribe">
					<p>To stop receiving these emails, <a class="button" href="http://localhost/Notifico/src/unsubscribe.php?email=' . urlencode($email) . '">Unsubscribe</a></p>
				</div>
			</div>
		</body>
		</html>
	';
	$headers = "From: no-reply@example.com\r\nContent-Type: text/html; charset=UTF-8\r\n";
	return mail($email, $subject, $body, $headers);
}
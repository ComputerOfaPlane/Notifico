# Notifico
A PHP-based task management system where users can add tasks to a common list and subscribe to receive hourly email reminders for pending tasks.

---

###### ⚠️ Important Note: use [Mailpit](https://mailpit.axllent.org/) for local testing of email functionality.

**Recommended PHP version: 8.3**

---

## 📌 Features

### 1️⃣ **Task Management**

- Add new tasks to the common list
- Duplicate tasks should not be added.
- Mark tasks as complete/incomplete
- Delete tasks
- Store tasks in `tasks.txt`

### 2️⃣ **Email Subscription System**

- Users can subscribe with their email
- Email verification process:
  - System generates a unique 6-digit verification code
  - Sends verification email with activation link
  - Link contains email and verification code
  - User clicks link to verify subscription
  - System moves email from pending to verified subscribers
- Store subscribers in `subscribers.txt`
- Store pending verifications in `pending_subscriptions.txt`

### 3️⃣ **Reminder System**

- CRON job runs every hour
- Sends emails to verified subscribers
- Only includes pending tasks in reminders
- Includes unsubscribe link in emails
- Unsubscribe process:
  - Every email includes an unsubscribe link
  - Link contains encoded email address
  - One-click unsubscribe removes email from subscribers

---

## 📜 File Details & Function Stubs

Implemented in the following functions in `functions.php`:

```php
function addTask($task_name) {
    // Add a new task to the list
}

function getAllTasks() {
    // Get all tasks from tasks.txt
}

function markTaskAsCompleted($task_id, $is_completed) {
    // Mark/unmark a task as complete
}

function deleteTask($task_id) {
    // Delete a task from the list
}

function generateVerificationCode() {
    // Generate a 6-digit verification code
}

function subscribeEmail($email) {
    // Add email to pending subscriptions and send verification
}

function verifySubscription($email, $code) {
    // Verify email subscription
}

function unsubscribeEmail($email) {
    // Remove email from subscribers list
}

function sendTaskReminders() {
    // Sends task reminders to all subscribers
 	// Internally calls  sendTaskEmail() for each subscriber
}

function sendTaskEmail( $email, $pending_tasks ) {
	// Sends a task reminder email to a subscriber with pending tasks.
}
```

## 📁 File Structure

- `functions.php` (Core functions)
- `index.php` (Main interface)
- `verify.php` (Email verification handler)
- `unsubscribe.php` (Unsubscribe handler)
- `cron.php` (Reminder sender)
- `setup_cron.sh` (CRON job setup)
- `tasks.txt` (Task storage)
- `subscribers.txt` (Verified subscribers)
- `pending_subscriptions.txt` (Pending verifications)

## 🔄 CRON Job Implementation

📌 **CRON job** that runs `cron.php` every 1 hour.  

---

### 🛠 Required Files

- **`setup_cron.sh`** (Must configure the CRON job)
- **`cron.php`** (Must handle sending GitHub updates via email)

---

### 🚀 How It Should Work

- The `setup_cron.sh` script should register a **CRON job** that executes `cron.php` every 1 hour.
- The CRON job **must be automatically added** when the script runs.
- The `cron.php` file should actually **fetch pending tasks** and **send emails** to subscribed users.

---

## 📩 Email Handling

✅ The email content is in **HTML format** (not JSON). 

✅ **PHP's `mail()` function** is used for sending emails.  

✅ Each email includes an **unsubscribe link**.  

✅ Stores subscribers email in `subscribers.txt`.

✅ Stores pending verifications in `pending_subscriptions.txt`.

✅ Each email includes an **unsubscribe link**.

---

## 📩 Email Content

#### ✅ Verification Email:

- **Subject:** `Verify subscription to Task Planner`
- **Body Format:**

```html
<p>Click the link below to verify your subscription to Task Planner:</p>
';
<p><a id="verification-link" href="{verification_link}">Verify Subscription</a></p>
```

- Sender: no-reply@example.com

---

#### ✅ Task Reminder Email:

- **Subject:** `Task Planner - Pending Tasks Reminder`
- **Body Format:**

```html
<h2>Pending Tasks Reminder</h2>
<p>Here are the current pending tasks:</p>
<ul>
	<li>Task 1</li>
	<li>Task 2</li>
</ul>
<p><a id="unsubscribe-link" href="{unsubscribe_link}">Unsubscribe from notifications</a></p>
```

---
## 📊 Data Storage Format

Data is stored in JSON format in the text files.

### Tasks Format (`tasks.txt`):

Tasks is stored as a JSON array of objects with the following schema:

```json
[
	{
		"id": "unique_task_id",
		"name": "Task Name",
		"completed": false
	},
	{
		"id": "another_task_id",
		"name": "Another Task",
		"completed": true
	}
]
```

### Subscribers Format (`subscribers.txt`):

Subscribers is stored as a JSON array of email addresses:

```json
["user1@example.com", "user2@example.com"]
```

### Pending Subscriptions Format (`pending_subscriptions.txt`):

Pending subscriptions is stored as a JSON object with emails as keys:

```json
{
	"user1@example.com": {
		"code": "123456",
		"timestamp": 1717694230
	},
	"user2@example.com": {
		"code": "654321",
		"timestamp": 1717694245
	}
}
```

---
## Demo Screenshots

![alt text](./demo/image.png)

![alt text](./demo/image3.png)

![alt text](./demo/image-1.png)

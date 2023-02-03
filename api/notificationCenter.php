<?php

/**
* API endpoint for the notification center
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('NotificationAPI.php');

$notificationAPI = new NotificationAPI();

$path = $_SERVER['REQUEST_URI'];

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		header('Content-Type: application/json');
		$userId=$_GET['user_id'] ?? 0;
		$notificationId=$_GET['notification_id'] ?? 0;
		switch ($_GET['method']){
			case 'getNotificationsByUserId' :
				$result=$notificationAPI->getNotificationsByUserId($userId);
				break;
			case 'getNotificationsCountByUserId' :
				$result=$notificationAPI->getNotificationsCountByUserId($userId);
				break;      
			case 'readOrUnread' :
				$result=$notificationAPI->getReadOrUnreadCountByUserId($userId);
				break;
			case 'removeNotificationByUserId' :
				$result=$notificationAPI->removeNotificationByUserId($userId, $notificationId);
				break;
			}
		echo json_encode($result);
		break;
	default:
	break;
}
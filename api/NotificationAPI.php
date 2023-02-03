<?php

require_once('../src/Database.php');
require_once('../src/classes/Notification.php');
require_once('../src/classes/Content.php');
require_once('../src/classes/Artist.php');
require_once('../src/classes/Playlist.php');
require_once('../src/classes/Album.php');
require_once('../src/classes/Podcast.php');
require_once('../src/classes/Track.php');
require_once('../src/classes/User.php');

/**
 * API for the notification center
 */
class NotificationAPI {
	
	private PDO $conn;

	public function __construct(){
		$database=new Database('localhost','mydb','root','C0urg3tte!');
		$this->conn = $database->getConn();
	}
	
	/**
	 * fetches all notifications for a given user
	 */
	public function getNotificationsByUserId(int $userId): array
	{
		$user = User::getUser($userId, $this->conn);
		$userNotifications = $user->getNotifications();

		$notificationData = [];
		foreach ($userNotifications as $notification) {

			//returns when a notification appeared
			$notificationDate = $notification->getValidityPeriod();
			$currentDate = new DateTime();
			$difference = $currentDate->diff($notificationDate);
			if ($difference->y > 0) {
				$time = $difference->y . " years ago";
			} elseif ($difference->m > 0) {
				$time = $difference->m . " months ago";
			} elseif ($difference->d > 0) {
				$time = $difference->d . " days ago";
			} elseif ($difference->h > 0) {
				$time = $difference->h . " hours ago";
			} elseif ($difference->i > 0) {
				$time = $difference->i . " minutes ago";
			} else {
				$time = "Just now";
			}
			
			//retrieves content of the notification based on content_id
			$contentId=$notification->getContentId();
			$content = Content::getContent($contentId,$this->conn);
			$contentType=$content->getContentType();

			$notifContent = [];
			
			switch ($contentType) {
			case 'artist':
					$artist = Artist::getArtist($contentId,$this->conn);
					$notifContent[] = ['name' => $artist->getName()];
					break;
				case 'album':
					$album = Album::getAlbum($contentId,$this->conn);
					$notifContent[] = ['name'=>$album->getName(),'artist'=>$album->getArtist()->getName()];
					break;
				case 'podcast':
					$podcast = Podcast::getPodcast($contentId,$this->conn);
					$notifContent[] = ['name' => $podcast->getName()];
					break;
				case 'playlist':
					$playlist = Playlist::getPlaylist($contentId,$this->conn);
					$notifContent[] = ['name' => $playlist->getName()];
					break;
				case 'track':
					$track = Track::getTrack($contentId,$this->conn);
					$notifContent[] = ['name' => $track->getName(),'album'=>$track->getAlbum()->getName()];
					break;
			}

			$notificationData[] = [
				'id' => $notification->getId(),
				'type' => $notification->getType(),
				'content_type' => $contentType,
				'content_id' => $contentId,
				'content' => $notifContent,
				'validity_period'=>$notification->getValidityPeriod(),
				'description'=>$notification->getDescription(),
				'is_read'=>$notification->getIsRead(),
				'time'=>$time
			];
		}
		
		return $notificationData;
	}
	
	/**
	 * fetches number of notifications for a given user
	 */
	public function getNotificationsCountByUserId(int $userId): int 
	{
		
		$user = User::getUser($userId, $this->conn);
		$userNotifications = $user->getNotifications();
		$count = count($userNotifications);

		return $count;
	}
		
	/**
	 * fetches total of read and unread notifications for a given user
	 */
	public function getReadOrUnreadCountByUserId(int $userId): array
	{
		$user = User::getUser($userId, $this->conn);
		$userNotifications = $user->getNotifications();
		
		$read = $unread = 0;
		
		foreach($userNotifications as $notif){
			if($notif->getIsRead()) $read++;
			else $unread++;
		}
		
		return ["read"=>$read,"unread"=>$unread];
	}
			
	/**
	 * removes given notification in database for a given user 
	 */
	public function removeNotificationByUserId(int $userId, int $notificationId): array
	{
		
		$user = User::getUser($userId, $this->conn);
		$user->removeNotificationForUser($notificationId, $this->conn);
		return $this->getNotificationsByUserId($userId);
	}
}
				

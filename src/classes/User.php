<?php
class User {
    private int $id;
    private string $name;
    private string $email;

    private array $notifications = [];

    public function __construct(int $id, string $name, string $email, array $notifications) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->notifications = $notifications;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getNotifications(): array {
        return $this->notifications;
    }

    public function setNotifications(array $notifications): void {
        $this->notifications = $notifications;
    }
    
	public static function getUser(int $id, PDO $conn) : User {
    
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $data = $stmt->fetch();
        
        //fetching all the user notifications
        $stmt = $conn->prepare("SELECT * FROM user_notifications WHERE user_id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $notifArray=[];
        foreach ($notifications as $notif){
            $notifObj=Notification::getNotification($notif['notification_id'], $conn);
            $notifObj->setIsRead($notif['is_read']);
            $notifArray[] = $notifObj;
        }
    
        //returning user with an array of notifications
        return new User($data["id"],$data["name"],$data["email"],$notifArray);
    }

    public function removeNotificationForUser(int $notificationId, $conn): void {

        $stmt = $conn->prepare(
			"DELETE FROM user_notifications 
			WHERE notification_id = :notification_id
			AND user_id = :user_id");
			
		$stmt->bindParam(':user_id', $this->id);
		$stmt->bindParam(':notification_id', $notificationId);
		$stmt->execute();
    }
}

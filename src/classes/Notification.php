<?php
class Notification {
    private int $id;
    private string $type;
    private int $contentId;
    private DateTime $validityPeriod;
    private string $description;
    private bool $isRead;

    public function __construct(int $id, string $type, int $contentId, DateTime $validityPeriod, string $description, bool $isRead = false) {
        $this->id = $id;
        $this->type = $type;
        $this->contentId = $contentId;
        $this->validityPeriod = $validityPeriod;
        $this->description = $description;
        $this->isRead = $isRead;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getType(): string {
        return $this->type;
    }

    public function setType(string $type): void {
        $this->type = $type;
    }

    public function getContentId(): int {
        return $this->contentId;
    }

    public function setContentId(int $contentId): void {
        $this->contentId = $contentId;
    }

    public function getValidityPeriod(): DateTime {
        return $this->validityPeriod;
    }

    public function setValidityPeriod(DateTime $validityPeriod): void {
        $this->validityPeriod = $validityPeriod;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getIsRead()
	{
		return $this->isRead;
	}

	public function setIsRead($isRead)
	{
		$this->isRead = $isRead;
	}
    
    public static function getNotification(int $id, PDO $conn) : Notification {
    
        $stmt = $conn->prepare("SELECT * FROM notifications WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $data = $stmt->fetch();
    
        // instantiate the Notification object and return it
        return new Notification($data["id"], $data["type"], $data["content_id"], new DateTime($data["validity_period"]), $data["description"]);
    }


}

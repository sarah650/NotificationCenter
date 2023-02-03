<?php

class Content {
    private $id;
    private $contentType;
    
    public function __construct($id, $contentType) {
        $this->id = $id;
        $this->contentType = $contentType;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getContentType() {
        return $this->contentType;
    }

    public static function getContent(int $id, PDO $conn) : Content {
    
        $stmt = $conn->prepare("SELECT * FROM content WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $data = $stmt->fetch();
    
        return new Content($data["id"], $data["type"]);
    }
}
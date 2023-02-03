<?php
class Artist extends Content {
    private string $name;
  
    public function __construct(int $id, string $name) {
      parent::__construct($id, 'artist');
      $this->name = $name;
    }
  
	/**
	 * Get the value of name
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * Set the value of name
	 */
	public function setName($name) : void
	{
		$this->name = $name;
	}

	    
    public static function getArtist(int $contentId, PDO $conn) : Artist {
    
        $stmt = $conn->prepare("SELECT * FROM artists WHERE content_id = :id");
        $stmt->bindParam(":id", $contentId);
        $stmt->execute();
        $data = $stmt->fetch();
    
        return new Artist($data["id"],$data["name"]);
    }

	public static function getArtistById(int $id, PDO $conn) : Artist {
    
        $stmt = $conn->prepare("SELECT * FROM artists WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $data = $stmt->fetch();
    
        return new Artist($data["id"],$data["name"]);
    }

}
<?php
class Podcast extends Content {
    private string $name;
  
    public function __construct(int $id, string $name) {
      parent::__construct($id, 'podcast');
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

	    
    public static function getPodcast(int $contentId, PDO $conn) : Podcast {
    
        $stmt = $conn->prepare("SELECT * FROM podcasts WHERE content_id = :id");
        $stmt->bindParam(":id", $contentId);
        $stmt->execute();
        $data = $stmt->fetch();
    
        return new Podcast($data["id"],$data["name"]);
    }

}
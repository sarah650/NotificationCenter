<?php

class Track extends Content {
	private string $name;
	private Album $album;
	
	public function __construct(int $id, string $name, Album $album) {
		parent::__construct($id, 'track');
		$this->name = $name;
		$this->album = $album;
	}
	
	public function getName() : string
	{
		return $this->name;
	}

	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	public static function getTrack(int $contentId, PDO $conn) : Track {
    
        $stmt = $conn->prepare("SELECT * FROM tracks WHERE content_id = :id");
        $stmt->bindParam(":id", $contentId);
        $stmt->execute();
        $data = $stmt->fetch();
    
        return new Track($data["id"],$data["name"],Album::getAlbumById($data["album_id"],$conn));
    }
	
	public static function getTrackById(int $id, PDO $conn) : Track {
    
        $stmt = $conn->prepare("SELECT * FROM tracks WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $data = $stmt->fetch();
    
        return new Track($data["id"],$data["name"],Album::getAlbumById($data["album_id"],$conn));
    }


	public function getAlbum()
	{
		return $this->album;
	}

	public function setAlbum(Album $album)
	{
		$this->album = $album;
	}
}
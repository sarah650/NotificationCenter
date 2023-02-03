<?php

class Album extends Content {
	private string $name;
	private Artist $artist;
	
	public function __construct(int $id, string $name, Artist $artist) {
		parent::__construct($id, 'album');
		$this->name = $name;
		$this->artist = $artist;
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
	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	/**
	 * Get the value of artist
	 */
	public function getArtist() : Artist
	{
		return $this->artist;
	}

	/**
	 * Set the value of artist
	 */
	public function setArtist(Artist $artist) : void
	{
		$this->artist = $artist;
	}

	public static function getAlbum(int $contentId, PDO $conn) : Album {
    
        $stmt = $conn->prepare("SELECT * FROM albums WHERE content_id = :id");
        $stmt->bindParam(":id", $contentId);
        $stmt->execute();
        $data = $stmt->fetch();
    
        return new Album($data["id"],$data["name"],Artist::getArtistById($data["artist_id"],$conn));
    }

	
	public static function getAlbumById(int $id, PDO $conn) : Album {
    
        $stmt = $conn->prepare("SELECT * FROM albums WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
		$data = $stmt->fetch();
    
        return new Album($data["id"],$data["name"],Artist::getArtistById($data["artist_id"],$conn));
    }

}
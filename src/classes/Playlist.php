<?php

class Playlist extends Content {
    private string $name;
	private array $albums;
  
    public function __construct(int $id, string $name, array $albums) {
      parent::__construct($id, 'playlist');
      $this->name = $name;
      $this->albums = $albums;
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
	public function setName($name) : Playlist
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get the value of albums
	 */
	public function getAlbums() : array
	{
		return $this->albums;
	}

	/**
	 * Set the value of albums
	 */
	public function setAlbums(array $albums) : Playlist
	{
		$this->albums = $albums;

		return $this;
	}

		    
    public static function getPlaylist(int $contentId, PDO $conn) : Playlist {
    
        $stmt = $conn->prepare("SELECT * FROM playlists WHERE content_id = :id");
        $stmt->bindParam(":id", $contentId);
        $stmt->execute();
        $pl = $stmt->fetch();
		
		$stmt = $conn->prepare("SELECT * FROM playlist_albums WHERE playlist_id = :id");
        $stmt->bindParam(":id", $pl['id']);
        $stmt->execute();
        $data = $stmt->fetchAll();

		$albums = [];
		foreach($data as $d){
			$albums[] = Album::getAlbumById($d["album_id"],$conn);
		}
    
        // instantiate the Notification object and return it
        return new Playlist($pl["id"],$pl["name"],$albums);
    }
}
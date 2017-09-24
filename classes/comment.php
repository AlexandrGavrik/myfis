<?php
class Comment{

  public $id = null;
  public $createdate = null;
  public $changedate = null;
  public $autor = null;
  public $summary = null;
  public $photo = null;
  public $content = null;
  public $status = null;
  public $articleid = null;
  public $commentid = null;
  public $liked = null;
  public $disliked = null;
  public $looked = null;
  

	public function __construct( $data=array() ) {
		if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
		if ( isset( $data['createdate'] ) ) $this->createdate = (int) $data['createdate'];
		if ( isset( $data['changedate'] ) ) $this->changedate = (int) $data['changedate'];
		if ( isset( $data['autor'] ) ) $this->autor = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Zа-яёА-ЯЁ0-9()]/u", "", $data['autor']);
		if ( isset( $data['summary'] ) ) $this->summary = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Zа-яёА-ЯЁ0-9()]/u", "", $data['summary'] );
		if ( isset( $data['photo'] ) ) $this->photo = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Zа-яёА-ЯЁ0-9()]/u", "", $data['photo'] );
		if ( isset( $data['content'] ) ) $this->content = $data['content'];
		if ( isset( $data['status'] ) ) $this->status = (int)$data['status'];
		if ( isset( $data['articleid'] ) ) $this->articleid = (int) $data['articleid'];
		if ( isset( $data['commentid'] ) ) $this->commentid = (int) $data['commentid'];
		if ( isset( $data['liked'] ) ) $this->liked = (int) $data['liked'];
		if ( isset( $data['disliked'] ) ) $this->disliked = (int) $data['disliked'];
		if ( isset( $data['looked'] ) ) $this->looked = (int) $data['looked'];

	}
	public function storeFormValues ( $params ) {
		$this->__construct( $params );
		if ( isset($params['createdate']) ) {
			$createdate = explode ( '-', $params['createdate'] );
			if ( count($createdate) == 3 ) {
				list ( $y, $m, $d ) = $createdate;
				$this->createdate = mktime ( 0, 0, 0, $m, $d, $y );
			}
		}else{
			$this->createdate = time();
		}
		if ( isset($params['changedate']) ) {
			$changedate = explode ( '-', $params['changedate'] );
			if ( count($changedate) == 3 ) {
				list ( $y, $m, $d ) = $changedate;
				$this->changedate = mktime ( 0, 0, 0, $m, $d, $y );
			}
		}else{
			$this->changedate = time();
		}
	}
	
	public static function getById( $id ) {
		$conn = new PDO( DSN, DUN, DUP, $OP );
		
		$sql = "SELECT *, UNIX_TIMESTAMP(createdate) AS createdate, UNIX_TIMESTAMP(changedate) AS changedate FROM ".CO." WHERE id = :id";
		$st = $conn->prepare( $sql );
		$st->bindValue( ":id", $id, PDO::PARAM_INT );
		$st->execute();
		$row = $st->fetch();
		$conn = null;
		if ( $row ) return new Comment( $row );
	}
	public static function getList( $arid, $numRows=1000000, $order="createdate DESC" ) {
		$conn = new PDO( DSN, DUN, DUP, $OP );
		$sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(createdate) AS createdate, UNIX_TIMESTAMP(changedate) AS changedate FROM ".CO."  WHERE articleid = :arid ORDER BY " .$order. " LIMIT :numRows";
		$st = $conn->prepare( $sql );
		$st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
		$st->bindValue( ":arid", $arid, PDO::PARAM_INT );
		$st->execute();
		$list = array();
		while ( $row = $st->fetch() ) {
			$comment = new Comment( $row );
			$list[] = $comment;
		}
		// Получаем общее количество статей, которые соответствуют критерию
		$sql = "SELECT FOUND_ROWS() AS totalRows";
		$totalRows = $conn->query( $sql )->fetch();
		$conn = null;
		return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
	}
	public function insert() {
		if ( !is_null( $this->id ) ) trigger_error ( "Comment::insert(): Attempt to insert an Comment object that already has its ID property set (to $this->id).", E_USER_ERROR );
		
		$conn = new PDO( DSN, DUN, DUP, $OP );
		$sql = "INSERT INTO ".CO." ( createdate, changedate, autor, summary, photo, content, status, articleid, commentid, liked, disliked, looked ) VALUES ( FROM_UNIXTIME(:createdate), FROM_UNIXTIME(:changedate), :autor, :summary, :photo, :content, :status, :articleid, :commentid, :liked, :disliked, :looked )";
		$st = $conn->prepare ( $sql );
		$st->bindValue( ":createdate", $this->createdate, PDO::PARAM_INT );
		$st->bindValue( ":changedate", $this->changedate, PDO::PARAM_INT );
		$st->bindValue( ":autor", $this->autor, PDO::PARAM_STR );
		$st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
		$st->bindValue( ":photo", $this->photo, PDO::PARAM_STR );
		$st->bindValue( ":content", $this->content, PDO::PARAM_STR );
		$st->bindValue( ":status", $this->status, PDO::PARAM_INT );
		$st->bindValue( ":commentid", $this->commentid, PDO::PARAM_INT );
		$st->bindValue( ":articleid", $this->articleid, PDO::PARAM_INT );
		$st->bindValue( ":liked", $this->liked, PDO::PARAM_INT );
		$st->bindValue( ":disliked", $this->disliked, PDO::PARAM_INT );
		$st->bindValue( ":looked", $this->looked, PDO::PARAM_INT );
		$st->execute();
		$this->id = $conn->lastInsertId();
		$conn = null;
	}
	 public function update() {
		if ( is_null( $this->id ) ) trigger_error ( "Comment::update(): Attempt to update an Comment object that does not have its ID property set.", E_USER_ERROR );
		$conn = new PDO( DSN, DUN, DUP, $OP );
		$sql = "UPDATE ".CO." SET createdate=FROM_UNIXTIME(:createdate), changedate=FROM_UNIXTIME(:changedate), autor=:autor, summary=:summary, photo=:photo, content=:content, status=:status, articleid=:articleid, commentid=:commentid, liked=:liked, disliked=:disliked, looked=:looked WHERE id = :id";
		$st = $conn->prepare ( $sql );
		$st->bindValue( ":createdate", $this->createdate, PDO::PARAM_INT );
		$st->bindValue( ":changedate", $this->changedate, PDO::PARAM_INT );
		$st->bindValue( ":autor", $this->autor, PDO::PARAM_STR );
		$st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
		$st->bindValue( ":photo", $this->photo, PDO::PARAM_STR );
		$st->bindValue( ":content", $this->content, PDO::PARAM_STR );
		$st->bindValue( ":status", $this->status, PDO::PARAM_INT );
		$st->bindValue( ":commentid", $this->commentid, PDO::PARAM_INT );
		$st->bindValue( ":articleid", $this->articleid, PDO::PARAM_INT );
		$st->bindValue( ":liked", $this->liked, PDO::PARAM_INT );
		$st->bindValue( ":disliked", $this->disliked, PDO::PARAM_INT );
		$st->bindValue( ":looked", $this->looked, PDO::PARAM_INT );
		$st->bindValue( ":id", $this->id, PDO::PARAM_INT );
		$st->execute();
		$conn = null;
	}
	public function delete() {
		if ( is_null( $this->id ) ) trigger_error ( "Comment::delete(): Attempt to delete an Comment object that does not have its ID property set.", E_USER_ERROR );
		$conn = new PDO( DSN, DUN, DUP, $OP );
		$st = $conn->prepare ( "DELETE FROM ".CO." WHERE id = :id LIMIT 1" );
		$st->bindValue( ":id", $this->id, PDO::PARAM_INT );
		$st->execute();
		$conn = null;
	}
	
	public static function counter( $id, $set ){
		$conn = new PDO( DSN, DUN, DUP, $OP );
		$sql = "SELECT ".$set." FROM ".CO." WHERE id = :id";
		$st = $conn->prepare( $sql );
		$st->bindValue( ":id", $id, PDO::PARAM_INT );
		$st->execute();
		$row = $st->fetch();
		$l=$row[0]+1;
		$sql = "UPDATE ".CO." SET ".$set."=:l WHERE id = :id";
		$st = $conn->prepare( $sql );
		$st->bindValue( ":l", $l, PDO::PARAM_INT );
		$st->bindValue( ":id", $id, PDO::PARAM_INT );
		$st->execute();
		$conn = null;
	}
	
	
}
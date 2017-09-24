<?php
class User{

  public $id = null;
  public $regdate = null;
  public $name = null;
  public $login = null;
  public $password = null;
  public $status = null;
  public $hashword = null;

	public function __construct( $data=array() ) {
		if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
		if ( isset( $data['regdate'] ) ) $this->regdate = (int) $data['regdate'];
		if ( isset( $data['name'] ) ) $this->name = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Zа-яёА-ЯЁ0-9()]/u", "", $data['name'] );
		if ( isset( $data['login'] ) ) $this->login = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Zа-яёА-ЯЁ0-9()]/u", "", $data['login'] );
		if ( isset( $data['password'] ) ) $this->password =  preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Zа-яёА-ЯЁ0-9()]/u", "", $data['password'] );
		if ( isset( $data['status'] ) ) $this->status = (int)$data['status'];
		if ( isset( $data['hashword'] ) ) $this->hashword = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Zа-яёА-ЯЁ0-9()]/u", "", $data['hashword']);
	}
	public function storeFormValues ( $params ) {
		$this->__construct( $params );
		if ( isset($params['regdate']) ) {
			$regdate = explode ( '-', $params['regdate'] );
			if ( count($regdate) == 3 ) {
				list ( $y, $m, $d ) = $regdate;
				$this->regdate = mktime ( 3, 0, 0, $m, $d, $y );
			}
		}
	}
	public static function getById( $id ) {
		$conn = new PDO( DSN, DUN, DUP, $OP );
		$sql = "SELECT *, UNIX_TIMESTAMP(regdate) AS regdate FROM ".US." WHERE id = :id";
		$st = $conn->prepare( $sql );
		$st->bindValue( ":id", $id, PDO::PARAM_INT );
		$st->execute();
		$row = $st->fetch();
		$conn = null;
		if ( $row ) return new Article( $row );
	}
	public static function getList( $numRows=1000000, $order="regdate DESC" ) {
		$conn = new PDO( DSN, DUN, DUP, $OP );
		$sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(regdate)  AS regdate FROM ".US."
			ORDER BY " .$order. " LIMIT :numRows";
		$st = $conn->prepare( $sql );
		$st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
		$st->execute();
		$list = array();
		while ( $row = $st->fetch() ) {
			$article = new Article( $row );
			$list[] = $article;
		}
		// Получаем общее количество статей, которые соответствуют критерию
		$sql = "SELECT FOUND_ROWS() AS totalRows";
		$totalRows = $conn->query( $sql )->fetch();
		$conn = null;
		return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
	}
	public function insert() {
		if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR );
		
		$conn = new PDO( DSN, DUN, DUP, $OP );
		$sql = "INSERT INTO ".US." ( regdate, name, login, password, status, hashword ) VALUES ( FROM_UNIXTIME(:regdate), :name, :login, :password, :status, :hashword)";
		$st = $conn->prepare ( $sql );
		$st->bindValue( ":regdate", $this->regdate, PDO::PARAM_INT );
		$st->bindValue( ":name", $this->name, PDO::PARAM_STR );
		$st->bindValue( ":login", $this->login, PDO::PARAM_STR );
		$st->bindValue( ":password", $this->password, PDO::PARAM_STR );
		$st->bindValue( ":status", $this->status, PDO::PARAM_INT );
		$st->bindValue( ":hashword", $this->hashword, PDO::PARAM_STR );
		$st->execute();
		$this->id = $conn->lastInsertId();
		$conn = null;
	}
	 public function update() {
		if ( is_null( $this->id ) ) trigger_error ( "Article::update(): Attempt to update an Article object that does not have its ID property set.", E_USER_ERROR );
		$conn = new PDO( DSN, DUN, DUP, $OP );
		$sql = "UPDATE ".US." SET regdate=FROM_UNIXTIME(:regdate), name=:name, login=:login, password=:password, status=:status, hashword=:hashword WHERE id = :id";
		$st = $conn->prepare ( $sql );
		$st->bindValue( ":regdate", $this->regdate, PDO::PARAM_INT );
		$st->bindValue( ":name", $this->name, PDO::PARAM_STR );
		$st->bindValue( ":login", $this->login, PDO::PARAM_STR );
		$st->bindValue( ":password", $this->password, PDO::PARAM_STR );
		$st->bindValue( ":status", $this->status, PDO::PARAM_INT );
		$st->bindValue( ":hashword", $this->hashword, PDO::PARAM_STR );
		$st->bindValue( ":id", $this->id, PDO::PARAM_INT );
		$st->execute();
		$conn = null;
	}
	public function delete() {
		if ( is_null( $this->id ) ) trigger_error ( "Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR );
		$conn = new PDO( DSN, DUN, DUP, $OP );
		$st = $conn->prepare ( "DELETE FROM ".US." WHERE id = :id LIMIT 1" );
		$st->bindValue( ":id", $this->id, PDO::PARAM_INT );
		$st->execute();
		$conn = null;
	}
	
}
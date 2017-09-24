<?php
class Ip{

  public $id = null;
  public $ip = null;
  public $login = null;
  public $regdate = null;
  public $action = null;
  public $articleid = null;
  
	public function __construct( $data=array() ) {
		if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
		if ( isset( $data['ip'] ) ) $this->ip = $data['ip'];
		if ( isset( $data['regdate'] ) ) $this->regdate = (int) $data['regdate'];
		if ( isset( $data['login'] ) ) $this->login = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Zа-яёА-ЯЁ0-9()]/u", "", $data['login']);
		if ( isset( $data['action'] ) ) $this->action = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Zа-яёА-ЯЁ0-9()]/u", "", $data['action'] );
		if ( isset( $data['articleid'] ) ) $this->articleid = (int)$data['articleid'];
	}
	public function storeFormValues ( $params ) {
		$this->__construct( $params );
		if ( isset($params['regdate']) ) {
			$regdate = explode ( '-', $params['regdate'] );
			if ( count($regdate) == 3 ) {
				list ( $y, $m, $d ) = $regdate;
				$this->regdate = mktime ( 0, 0, 0, $m, $d, $y );
			}
		}else{
			$this->regdate = time();
		}
	}
	public static function checkIp( $ip, $articleid ) {
		$conn = new PDO( DSN, DUN, DUP, $OP );
		$sql = "SELECT * FROM ".IP." WHERE (articleid=:articleid and ip=:ip)"; 
		$st = $conn->prepare( $sql );
		$st->bindValue( ":articleid", $articleid, PDO::PARAM_INT );
		$st->bindValue( ":ip", $ip, PDO::PARAM_STR );
		$st->execute();
		$row = $st->fetch();
		$conn = null;
		if ( $row ) return new Ip( $row );
	}
	public function insert() {
		$conn = new PDO( DSN, DUN, DUP, $OP );
		$sql = "INSERT INTO ".IP." ( ip, regdate, login, action, articleid ) VALUES ( :ip, FROM_UNIXTIME(:regdate), :login, :action, :articleid)";
		$st = $conn->prepare ( $sql );
		$st->bindValue( ":ip", $this->ip, PDO::PARAM_STR );
		$st->bindValue( ":login", $this->login, PDO::PARAM_STR );
		$st->bindValue( ":regdate", $this->regdate, PDO::PARAM_INT );
		$st->bindValue( ":action", $this->action, PDO::PARAM_STR );
		$st->bindValue( ":articleid", $this->articleid, PDO::PARAM_INT );
		$st->execute();
		$this->id = $conn->lastInsertId();
		$conn = null;
	}	
}
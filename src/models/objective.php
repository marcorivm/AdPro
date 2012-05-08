<?php
	require_once('inc/connection.php');
	
	class objective {
		private $_id;
		private $_name;
		private $_type;
		private $_description;
		private $_importance;
		
		public function __construct($opts){
				if(is_array($opts)){
					$this->setOptions($opts);
				} else {
					$this->_id = $opts;
					$_query = "SELECT * FROM objectives WHERE id=".$this->_id;
					$_execQuery = mysql_query($_query);
					$_qR = mysql_fetch_array($_execQuery);
					$this->_name = $_qR['name'];
					$this->_description = $_qR['description'];
					$this->_type = $_qR['type'];
					$this->_importance = $_qR['importance'];
				}
		}
		public function setOptions(array $opts){
			$this->_id = $opts['id'];
			$this->_name = $opts['name'];
			$this->_description = $opts['description'];
			$this->_type = $opts['type'];
			$this->_importance = $opts['importance'];
		}
		
		public function getId() {return $this->_id; }
		public function getName() {return $this->_name; }
		public function getType(){return $this->_type; }
		public function getDescription() {return $this->_description; }
		public function getImportance() {return $this->_importance; }
		
		public function getScore($score){
			$final_score;
			if($this->_type==0){
				switch($score) {
					case 0:
					$final_score = "No";
					break;
					
					case 100:
					$final_score = "Si";
					break;
					
					default:
					$final_score = "N/A";
				}
			} else {
				$final_score = ($this->_importance/100)*$score;
			}
			return $final_score;
		}
		
		public function isAccepted($score){
			if($this->_type==0 && $score==0) {
				return false;
			} else {
				return true;
			}
		}
	}
	
	function getObjectives(){
		$_query = "SELECT * FROM objectives ORDER BY `type`, id ASC";
		$_execQuery = mysql_query($_query);
		$objectives = array();
		while($_qR = mysql_fetch_array($_execQuery)){
			$objectives[] = new objective($_qR);
		}
		return $objectives;
	}
	
	function deleteObjective($obj_id){		
		$_query = "DELETE FROM projects_objectives WHERE id_obj=".$obj_id;
		$_execQuery = mysql_query($_query);
		
		$_query = "DELETE FROM evaluations_objectives WHERE id_obj=".$obj_id;
		$_execQuery = mysql_query($_query);
		
		$_query = "DELETE FROM objectives WHERE id=".$obj_id;
		$_execQuery = mysql_query($_query);
	}
	
	function newObjective($obj_data){
		$_type = ($obj_data[2]['value'] == -1)? 0 : 1;
		$_importance = ($obj_data[2]['value'] == -1)? 'NULL' : $obj_data[2]['value'];
		$_query = "INSERT INTO objectives VALUES('','".$obj_data[1]['value']."','".$_type."','".$obj_data[3]['value']."', ".$_importance.")";
		$_execQuery = mysql_query($_query);
		return mysql_insert_id();
	}
?>
<?php
	require_once('inc/connection.php');
	
	class project {
		private $_id;
		private $_name;
		private $_description;
		private $_userId;
		private $_acceptable = true;
		private $_total_score = 0;
		
		public function __construct($opts){
				if(is_array($opts)){
					$this->setOptions($opts);
				} else {
					$this->_id = $opts;
					$_query = "SELECT * FROM projects WHERE id=".$this->_id;
					$_execQuery = mysql_query($_query);
					$_qR = mysql_fetch_array($_execQuery);
					$this->_name = $_qR['name'];
					$this->_description = $_qR['description'];
					$this->_userId = $_qR['user_id'];
				}
		}
		public function setOptions(array $opts){
			$this->_id = $opts['id'];
			$this->_description = $opts['description'];
			$this->_name = $opts['name'];
			$this->_userId = $opts['user_id'];
		}
		
		public function getId() {return $this->_id; }
		public function getName() {return $this->_name; }
		public function getDescription() {return $this->_description; }
		public function getUserId() {return $this->_user_id; }
		public function getTotalScore() {return $this->_total_score; }
		public function isAcceptable() {return $this->_acceptable; }
		public function resetValues() {$this->_acceptable = true; $this->_total_score=0;}
		public function setAcceptable($isAcceptable) {
			$this->_acceptable = $this->_acceptable && $isAcceptable;
		}
		public function addToScore($toAdd) {
			$this->_total_score += $toAdd;
		}
	}
	
	function newProject ($proj_data){
		$_query = "INSERT INTO projects VALUES('','".$proj_data[1]['value']."','".$proj_data[2]['value']."',".$proj_data[0]['value'].")";
		$_execQuery = mysql_query($_query);
		return mysql_insert_id();
	}
	
	function deleteProject($proj_id){		
		$_query = "DELETE FROM projects_objectives WHERE id_pro=".$proj_id;
		$_execQuery = mysql_query($_query);
		
		$_query = "DELETE FROM evaluations_projects WHERE id_pro=".$proj_id;
		$_execQuery = mysql_query($_query);
		
		$_query = "DELETE FROM projects WHERE id=".$proj_id;
		$_execQuery = mysql_query($_query);
	}
	
	function getProjects(){
		$_query = "SELECT * FROM projects";
		$_execQuery = mysql_query($_query);
		$projects = array();
		while($_qR = mysql_fetch_array($_execQuery)){
			$projects[] = new project($_qR);
		}
		return $projects;
	}
?>
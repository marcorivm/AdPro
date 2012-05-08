<?php
	require_once('inc/connection.php');
	require_once('models/project.php');
	require_once('models/objective.php');
	
	class evaluation {
		private $_id;
		private $_name;
		private $_userId;
		private $_description;
		private $_created;
		private $_modified;
		private $_projects;
		private $_objectives;
		private $_proj_obj;
		
		public function __construct($opts){
				
				if(is_array($opts)){
					$this->setOptions($opts);
				} else {
					$this->_id = $opts;
					$_query = "SELECT * FROM evaluations WHERE id=".$opts;
					$_execQuery = mysql_query($_query);
					$_qR = mysql_fetch_array($_execQuery);
					$this->_userId = $_qR['user_id'];
					$this->_name = $_qR['name'];
					$this->_description = $_qR['description'];
					$this->_created = $_qR['created'];
					$this->_modified = $_qR['modified'];
				}
		}
		
		
		public function setOptions(array $opts){
			$this->_id = $opts['id'];
			$this->_userId = $opts['user_id'];
			$this->_name = $opts['name'];
			$this->_description = $opts['description'];
			$this->_created = $opts['created'];
			$this->_modified = $opts['modified'];
		}
		
		public function getId() {return $this->_id; }
		public function getUserId() {return $this->_userId; }
		public function getName(){ return $this->_name; }
		public function getDescription() {return $this->_description; }
		public function getCreated() {return $this->_created; }
		public function getModified() { return $this->_modified; }		
		public function getShortDescription() {return substr($this->_description,0, 150)."..."; }
		
		public function getProjects() {
			if(!is_array($this->_projects)){
				$this->loadProjects();
			}
			return $this->_projects;
		}
		
		public function getObjectives($type) {
			if(!is_array($this->_objectives)){
				$this->loadObjectives();
			}
			$tmp_obj = array();
			if($type == 0) {
				foreach($this->_objectives as $currObj){
					if($currObj->getType() == 0){
						$tmp_obj[] = $currObj;
					}
				};
			} else if ($type == 1) {
				foreach($this->_objectives as $currObj){
					if($currObj->getType() == 1){
						$tmp_obj[] = $currObj;
					}
				}
			} else {
				$tmp_obj = $this->_objectives;
			}
			
			return $tmp_obj;
		}
		
		public function getProjectObjectives() {
			if(!is_array($this->_proj_obj)){
				$this->loadProjectObjectives();
			}
			return $this->_proj_obj;
		}
		
		public function loadProjectObjectives(){
			$_query = "SELECT * FROM projects_objectives WHERE id_eval=".$this->_id." ORDER BY id_obj";
			$_execQuery = mysql_query($_query);
			while($_qR = mysql_fetch_array($_execQuery)){
				$this->_proj_obj[$_qR['id_obj']][$_qR['id_pro']] = $_qR['score'];
			}
		}
		
		public function loadProjects() {
			$_query = "SELECT * FROM evaluations_projects WHERE id_eval=".$this->_id;
			$_execQuery = mysql_query($_query);
			$this->_projects = array();
			while($_qR = mysql_fetch_array($_execQuery)){
				$this->_projects[] = new project($_qR['id_pro']);
			}
			
		}
		
		public function loadObjectives() {
			$_query = "SELECT * FROM evaluations_objectives WHERE id_eval=".$this->_id;
			$_execQuery = mysql_query($_query);
			$this->_objectives = array();
			while($_qR = mysql_fetch_array($_execQuery)){
				$this->_objectives[] = new objective($_qR['id_obj']);
			}
			
		}
		
		public function getNotUsedProjects(){
			$_query = "SELECT id FROM projects WHERE id NOT IN (SELECT id_pro as id FROM evaluations_projects WHERE id_eval=".$this->_id.")";
			$_execQuery = mysql_query($_query);
			$_tmp_arr = array();
			while($_qR = mysql_fetch_array($_execQuery)){
				$_tmp_arr[] = new project($_qR['id']);
			}
			return $_tmp_arr;
		}
		
		
		public function getNotUsedObjectives($obj_type){
			$_query = "SELECT id FROM objectives WHERE id NOT IN (SELECT id_obj as id FROM evaluations_objectives WHERE id_eval=".$this->_id.") AND `type`=".$obj_type;
			$_execQuery = mysql_query($_query);
			$_tmp_arr = array();
			while($_qR = mysql_fetch_array($_execQuery)){
				$_tmp_arr[] = new objective($_qR['id']);
			}
			return $_tmp_arr;
		}
		
		public function update(){
			$_query = "UPDATE evaluations SET modified=NOW() WHERE id=".$this->_id;
			$_execQuery = mysql_query($_query);
		}
	}
	function newEvaluation($eval_data) {
		$_query = "INSERT INTO evaluations VALUES('',".$eval_data[0]['value'].",'".$eval_data[1]['value']."','".$eval_data[2]['value']."', NOW(), NOW())";
		$_execQuery = mysql_query($_query);
		return mysql_insert_id();
	}
	function getEvaluations(){
		$_query = "SELECT * FROM evaluations";
		$_execQuery = mysql_query($_query);
		$evaluations = array();
		while($_qR = mysql_fetch_array($_execQuery)){
			$evaluations[] = new evaluation($_qR);
		}
		return $evaluations;
	}
	
	function addProject($proj_id, $eval_id, $obj_values){
		$_query = "DELETE FROM projects_objectives WHERE id_pro=".$proj_id." AND id_eval=".$eval_id;
		$_execQuery = mysql_query($_query);
		$_query = "DELETE FROM evaluations_projects WHERE id_pro=".$proj_id." AND id_eval=".$eval_id;
		$_execQuery = mysql_query($_query);
		$_query = "INSERT INTO evaluations_projects VALUES ('',".$eval_id.",".$proj_id.")";
		$_execQuery = mysql_query($_query);
		
		foreach($obj_values as $currObj){
			$_query = "INSERT INTO projects_objectives VALUES ('',".$proj_id.",".$currObj['name'].",".$eval_id.",".$currObj['value'].")";
			$_execQuery = mysql_query($_query);
		}
	}
	
	
	function addObjective($obj, $eval_id){
		$def_value = ($obj->getType() == 1) ? 0 : 50;
		$_query = "DELETE FROM projects_objectives WHERE id_obj=".$obj->getId()." AND id_eval=".$eval_id;
		$_execQuery = mysql_query($_query);
		$_query = "DELETE FROM evaluations_objectives WHERE id_obj=".$obj->getId()." AND id_eval=".$eval_id;
		$_execQuery = mysql_query($_query);
		$_query = "INSERT INTO evaluations_objectives VALUES ('',".$eval_id.",".$obj->getId().")";
		$_execQuery = mysql_query($_query);
		$_query = "SELECT id_pro FROM evaluations_projects WHERE id_eval=".$eval_id;
		$_execQuery = mysql_query($_query);
		while($_qR = mysql_fetch_array($_execQuery)){
			$_query = "INSERT INTO projects_objectives VALUES ('',".$_qR['id_pro'].",".$obj->getId().",".$eval_id.",".$def_value.")";
			$_execAuxQuery = mysql_query($_query);
		}
	}
	
	function deleteEvaluation($eval_id){		
		$_query = "DELETE FROM projects_objectives WHERE id_eval=".$eval_id;
		$_execQuery = mysql_query($_query);
		
		$_query = "DELETE FROM evaluations_projects WHERE id_eval=".$eval_id;
		$_execQuery = mysql_query($_query);
		
		$_query = "DELETE FROM evaluations_objectives WHERE id_eval=".$eval_id;
		$_execQuery = mysql_query($_query);
		
		$_query = "DELETE FROM evaluations WHERE id=".$eval_id;
		$_execQuery = mysql_query($_query);
	}
	
	function removeProject($proj_id, $eval_id){		
		$_query = "DELETE FROM projects_objectives WHERE id_pro=".$proj_id." AND id_eval=".$eval_id;
		$_execQuery = mysql_query($_query);
		
		$_query = "DELETE FROM evaluations_projects WHERE id_pro=".$proj_id." AND id_eval=".$eval_id;
		$_execQuery = mysql_query($_query);
	}
	
	function removeObjective($obj_id, $eval_id){
		$_query = "DELETE FROM projects_objectives WHERE id_obj=".$obj_id." AND id_eval=".$eval_id;
		$_execQuery = mysql_query($_query);
		
		$_query = "DELETE FROM evaluations_objectives WHERE id_obj=".$obj_id." AND id_eval=".$eval_id;
		$_execQuery = mysql_query($_query);
	}
	
	function findEvaluations($id, $type = 0){
		// $type == 0 projects
		// $type == 1 objectives
		$_query = "SELECT Eval.* FROM evaluations as Eval, evaluations_";
		if($type == 0) {
			$_query .= "projects as Pro WHERE Eval.id=Pro.id_eval AND Pro.id_pro=";
		} else {
			$_query .= "objectives as Obj WHERE Eval.id=Obj.id_eval AND Obj.id_obj=";
		}
		$_query .= $id;
		$_evaluations = array();
		$_execQuery = mysql_query($_query);
		while($_qR = mysql_fetch_array($_execQuery)){
			$_evaluations[] = new evaluation($_qR);
		}
		
		return $_evaluations;
	}
?>
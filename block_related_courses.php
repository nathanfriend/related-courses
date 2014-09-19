<?php 

class block_related_courses extends block_list {
    
    function init() {
        $this->title = get_string('relatedcourses', 'block_related_courses');
        $this->version = 2011111100;
    }

    function get_content() {
        global $CFG, $course, $DB;
        
        //print_object($course);
        
        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->icons = array();
        
        // Following the explanation in http://docs.moodle.org/en/Metacourses
		$sql = "SELECT c.id, c.fullname 
		FROM {enrol} e, {course} c 
		WHERE e.enrol = 'meta'
		AND e.courseid = :courseid
		AND e.customint1 = c.id
		AND visible = '1'
                ORDER BY c.fullname ASC";
		
		$rs = $DB->get_recordset_sql($sql, array('courseid'=>$course->id));
		if(count($rs) > 0) {
        // you are in a metacourse, you are looking for the parent
			foreach($rs as $parent) {
                $this->content->items[] = '<a href="'.$CFG->wwwroot.'/course/view.php?id='.$parent->id.'">'. format_string($parent->fullname).'</a><br />';
			
                
                        }     
                }
		
        // Now check if this course has any children
		$sql = "SELECT c.id, c.fullname
		FROM {enrol} e, {course} c 
		WHERE e.enrol = 'meta' 
		AND e.customint1 = :courseid 
		AND e.courseid = c.id
		AND visible = '1'
                ORDER BY c.fullname ASC";
		
		$rs = $DB->get_recordset_sql($sql, array('courseid'=>$course->id));
                if(count($rs) > 0) {
		// We have some children
			foreach($rs as $parent) {
				$this->content->items[] = '<a href="'.$CFG->wwwroot.'/course/view.php?id='.$parent->id.'">'. format_string($parent->fullname).'</a><br />';
			}
		}

        $this->content->footer = '';
    }
}

?>

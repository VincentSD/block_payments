<?php
// $test = $_SERVER['REQUEST_URI'];
// $idd = $_GET['id'];
// global $idd; 
// echo $idd;
//require_once($_SERVER['DOCUMENT_ROOT'].'enrol/flutterwave/enrolment_form.php');
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Form for editing HTML block instances.
 * @author    Vince 2020
 * @package   block_payments
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_payments extends block_base {
    public function paynow(){
        $url = 'https://flutterwave.com/pay/4vsqfdmtjumy';
        $mform = &$this->_form;
        $mform->addElement();
        echo "Hello";
    }


    function init() {
        $this->title = get_string('pluginname', 'block_payments');
    }

    function has_config() {
        return true;
    }


    public function ttable($balance){
        if($balance>0){
            echo "You are owing $balance";
        }else{
            echo "We owe you $balance";
        }
    }
    function get_content() {
        global $DB, $USER;

        if ($this->content !== NULL) {
            return $this->content;
        }


        $content = '';

        $showcourses = get_config('block_payments', 'showcourses');

        if ($showcourses) {
            $courses = $DB->get_records('course');
            foreach ($courses as $course) {
                $content .= $course->fullname . '<br>';
            }
        }
        else {
            $trans = $DB->get_records('enrol_flutterwave');
            // $thissub = $this->page->course;
            // print_r($thissub);
            
            // $sql = "SELECT *
            //         FROM public.mdl_enrol_flutterwave
            //         WHERE courseid = 2 and userid = 3";
            // $params = array('courseid' => 2, 'userid' => 3);
            // $trans = $DB->get_record_sql($sql, $params, IGNORE_MULTIPLE);and $rec->courseid==$_GET['id']
            foreach ($trans as $rec) {
                //echo "<script>alert('". $rec->userid. "')</script>";
                
                if ($USER->id == $rec->userid && $rec->courseid == (isset($_GET['id']) ? trim($_GET['id']) : '')){
                    $total_cost = $rec->total_cost;
                    $amount = $rec->amount;
                    $courseid = $rec->courseid;
                    $cname = $DB->get_record('course', array('id' => $courseid), $fields='shortname', $strictness=IGNORE_MISSING)->shortname;
                    $balance = $amount- $total_cost;
                    $cur = $rec->currency;
                    $link = '<a href="https://flutterwave.com/pay/4vsqfdmtjumy">Click to pay your fees</a>';
                    $redirect = '<a href="http://localhost/moodle/enrol/index.php?id='.$courseid.'">Click to pay your fees</a>';
                    $tab = '<html>

                    <head>
                    
                    </head> 
                    
                    <body>
                    
                    <table border="1px" cellpadding="4" cellspacing="50">
                    
                    <tr>
                    
                    <td>Course</td>
                    
                    <td>Cost (GHS)</td>
                                    
                    <td>Amount Paid (GHS)</td>

                    <td>Balance (GHS)</td>

                    
                    </tr>
                    
                    <tr>
                    <td>'.$cname .'</td>
                    <td>'.$total_cost.'</td>
                    <td>'.$amount.'</td>
                    <td>'.$balance.'</td>
                    
                    </tr>
                    
                    </table>
                    
                    </body>
                    
                    </html>';



                    // $content .= 'Transactions'.'<br>';
                    //$content .= '---------------------------------------------------------------'.'<br>';
                    $content .= $tab;
                    $content .= $redirect;
                    $content .= '<br>  <br>';
                    
                }
            $feet = 'abc';
            }
        }
       
        $this->content = new stdClass;
        $this->content->text = $content;
        $this->content->footer = 'Please consider satifying your financial obligations'; //'this is the footer';
        return $this->content;
    }
}

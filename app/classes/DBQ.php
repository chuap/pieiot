<?php  

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DbQueryLog
 *
 * @author choonewza
 */
class DBQ {

    const SHOW = TRUE;

    public static function queryLog() {
        if (DBQ::SHOW) {
            foreach (DB::getQueryLog() as $key=>$sql) {
                echo "<p>";
                echo "SQL" . ($key + 1) . ": " . $sql['query'];
                echo "<br/>";
                echo "- Bindings: ";
                foreach ($sql['bindings'] as $value) {
                    echo "'" . $value . ",' ";
                }
                echo "<br/>";
                echo "- Time: " . $sql['time'];
                echo "</p>";
            }
        }
    }

}

<?php

class Crontab {
    
    // In this class, array instead of string would be the standard input / output format.
    // Legacy way to add a job:
    // $output = shell_exec('(crontab -l; echo "'.$job.'") | crontab -');
    // systemctl restart cron
    // ps -ef | grep cron | grep -v grep
    // sudo service cron start
    // sudo /sbin/service cron start
	//$output = shell_exec("apt-get -y install <package-id>");
	//echo $output;
    static private function stringToArray($jobs = '') {
        $array = explode("\r\n", trim($jobs)); // trim() gets rid of the last \r\n
        foreach ($array as $key => $item) {
            if ($item == '') {
                unset($array[$key]);
            }
        }
        return $array;
    }
    
    static private function arrayToString($jobs = array()) {
        $string = implode("\r\n", $jobs);
        return $string;
    }
    
    static public function getJobs() {
        $output = shell_exec('crontab -l');
        return self::stringToArray($output);
    }

    static public function removeAllJobs() {
        $output = shell_exec(' crontab -r');
        return self::stringToArray($output);
    }
    
    static public function saveJobs($jobs = '') {
        $output = shell_exec('echo "' . $jobs . '" | crontab -');
        return $output;	
    }
    
    static public function doesJobExist($job = '') {
        $jobs = self::getJobs();
        if (in_array($job, $jobs)) {
            return true;
        } else {
            return false;
        }
    }
    
    static public function addJob($job = '') {
        if (self::doesJobExist($job)) {
            return false;
        } else {
            $jobs = self::getJobs();
            $jobs[] = $job;
            return self::saveJobs($jobs);
        }
    }
    
    static public function removeJob($job = '') {
        if (self::doesJobExist($job)) {
            $jobs = self::getJobs();
            unset($jobs[array_search($job, $jobs)]);
            return self::saveJobs($jobs);
        } else {
            return false;
        }
    }
    
}
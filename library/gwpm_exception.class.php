<?php

class GwpmCommonException extends Exception {

    function GwpmCommonException($message = '') {
    	echo '<b> GwpmCommonException: </b>' . $message ;
    	// throw new Exception($message) ;  
    	appendLog($message) ;
    }
    
}
<?php
// James DO
function MCAuthorization($jwt){
        if(valid($jwt)){
            if(almost expired){
                renew($jwt);
                do_things;
                return [$newjwt, $response];
            }
            else{
                do_things;
                return [$jwt, $response];
            }
        }
        else{
            return 401
        }
    }
    function valid($jwt){
        //try decode
        //if decoded
            // see if:
                // 1 token expired
                // 2 mark need reissue
            //all pass, return true
        //else
            // return false        
    }
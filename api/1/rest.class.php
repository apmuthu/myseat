<?php
// JASON status messages
function getStatusCodeMessage($status) {
		// these could be stored in a .ini file and loaded
		// via parse_ini_file()... however, this will suffice
		// for an example
		$codes = Array(
		    100 => 'Continue',
		    101 => 'Switching Protocols',
		    200 => 'OK',
		    201 => 'Created',
		    202 => 'Accepted',
		    203 => 'Non-Authoritative Information',
		    204 => 'No Content',
		    205 => 'Reset Content',
		    206 => 'Partial Content',
		    300 => 'Multiple Choices',
		    301 => 'Moved Permanently',
		    302 => 'Found',
		    303 => 'See Other',
		    304 => 'Not Modified',
		    305 => 'Use Proxy',
		    306 => '(Unused)',
		    307 => 'Temporary Redirect',
		    400 => 'Bad Request',
		    401 => 'Unauthorized',
		    402 => 'Payment Required',
		    403 => 'Forbidden',
		    404 => 'Not Found',
		    405 => 'Method Not Allowed',
		    406 => 'Not Acceptable',
		    407 => 'Proxy Authentication Required',
		    408 => 'Request Timeout',
		    409 => 'Conflict',
		    410 => 'Gone',
		    411 => 'Length Required',
		    412 => 'Precondition Failed',
		    413 => 'Request Entity Too Large',
		    414 => 'Request-URI Too Long',
		    415 => 'Unsupported Media Type',
		    416 => 'Requested Range Not Satisfiable',
		    417 => 'Expectation Failed',
		    500 => 'Internal Server Error',
		    501 => 'Not Implemented',
		    502 => 'Bad Gateway',
		    503 => 'Service Unavailable',
		    504 => 'Gateway Timeout',
		    505 => 'HTTP Version Not Supported'
		);

		return (isset($codes[$status])) ? $codes[$status] : '';
}
   
// Pretty print JSON
function prettifyJson($json) {
        $tab = "  ";
        $new_json = "";
        $indent_level = 0;
        $in_string = false;
    
        $json_obj = json_decode($json);
    
        if($json_obj === false)
            return false;
    
        $json = json_encode($json_obj);
        $len = strlen($json);
    
        for($c = 0; $c < $len; $c++)
        {
            $char = $json[$c];
            switch($char)
            {
                case '{':
                case '[':
                    if(!$in_string)
                    {
                        $new_json .= $char . "\n" . str_repeat($tab, $indent_level+1);
                        $indent_level++;
                    }
                    else
                    {
                        $new_json .= $char;
                    }
                    break;
                case '}':
                case ']':
                    if(!$in_string)
                    {
                        $indent_level--;
                        $new_json .= "\n" . str_repeat($tab, $indent_level) . $char;
                    }
                    else
                    {
                        $new_json .= $char;
                    }
                    break;
                case ',':
                    if(!$in_string)
                    {
                        $new_json .= ",\n" . str_repeat($tab, $indent_level);
                    }
                    else
                    {
                        $new_json .= $char;
                    }
                    break;
                case ':':
                    if(!$in_string)
                    {
                        $new_json .= ": ";
                    }
                    else
                    {
                        $new_json .= $char;
                    }
                    break;
                case '"':
                    if($c > 0 && $json[$c-1] != '\\')
                    {
                        $in_string = !$in_string;
                    }
                default:
                    $new_json .= $char;
                    break;                   
            }
        }
    
        return $new_json;
}

// maximum requests per token
function max_requests() {
    return 1000;
}

function validate_requests($api_token='') {
    GLOBAL $status;

    $sql = "SELECT request_count, last_login FROM `api_users` WHERE `token` = '".$api_token."'";
    $select = query($sql);
    $login = getRowList($select);

    if (!empty($login)) {
        $now = date('Y-m-d');
        // count API requests per day
        if ($login->last_login == $now ) {
            $sql = "UPDATE `api_users` SET 
                `request_count` = request_count+1,
                WHERE `token` = '".$api_token."'";
        } else {
            $sql = "UPDATE `api_users` SET 
                `request_count` = 1,  
                `last_login` = CURDATE()
                WHERE `token` = '".$api_token."'";
        }
        $update = query($sql);
    } else {
            // create some error code/message
            $status['code'] = 400;
            $status['status'] = getStatusCodeMessage($status['code']); 
    }
        // handle back the number of requests per day
        return $login->request_count;
}

// verify URL
function is_url($url) {
    return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

// generate API key
function generate_key() {
  $serial = sha1(uniqid(rand(), true));
  $checksum = substr(md5($serial), 0, 4);
  return $serial . $checksum;
}

// verify API key
function verify_key($key) {
  $serial = substr($key, 0, 40);
  $checksum = substr($key, -4);
  return md5($serial, 0, 4) == $checksum;
}

// verify API key
function return_result_json($result) {
    // encode results to json
    $return_obj = json_encode($result);
    
    // blank JSON
    echo $return_obj;

    // nice JSON
    /*
    echo header('Content-type: text/plain; Charset=utf-8;');
    echo "result:\n";
    echo prettifyJson($return_obj);
    */

    // Free connection resources.   
    mysql_close();
}

?>
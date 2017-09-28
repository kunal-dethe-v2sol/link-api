<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Mailer\Email;

class CommonComponent extends Component {

    /**
     * Response Status Code array.
     */
    public $response_codes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => "I'm a teapot",
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    ];

    /**
     * Saves the request params for the single request.
     */
    public $_requestParams = array();

    public function initialize(array $config) {
        
    }

    /**
     * getRequestParams
     * 
     * Reads the Request Params made for the POST, PUT and DELETE.
     * 
     * @access public
     * @return array
     */
    public function getRequestParams() {
        if (empty($this->_requestParams)) {
            $params = array();
            $request_vars = file_get_contents('php://input');
            if (strlen($request_vars)) {
                $params = json_decode($request_vars);
                if ($params) {
                    $params = (array) $params;
                } else {
                    parse_str($request_vars, $params);
                }
            }
            $this->_requestParams = $params;
        }
        return $this->_requestParams;
    }

    public function toastErrorMessages($errors) {
        $error_msg = [];
        // Array depth 4 is observed when validation errors occur while saving models with associations
        // https://book.cakephp.org/3.0/en/orm/saving-data.html#saving-with-associations
        if ($this->_array_depth($errors) == 4) {
            foreach ($errors as $model_errors) {
                foreach ($model_errors as $record_errors) {
                    foreach ($record_errors as $field_name => $error) {
                        $error_message = $error;
                        if (is_array($error)) {
                            $error_message = implode("|", $error);
                        }
                        $error_msg[$field_name] = $error_message;
                    }
                }
            }
        } else {
            foreach ($errors as $field_name => $error) {
                $error_message = $error;
                if (is_array($error)) {
                    $error_message = implode("|", $error);
                }
                $error_msg[$field_name] = $error_message;
            }
        }
        return ['errors' => $error_msg];
    }

    private function _array_depth($array) {
        $max_indentation = 1;
        $array_str = print_r($array, true);
        $lines = explode("\n", $array_str);
        foreach ($lines as $line) {
            $indentation = (strlen($line) - strlen(ltrim($line))) / 4;
            if ($indentation > $max_indentation) {
                $max_indentation = $indentation;
            }
        }
        return ceil(($max_indentation - 1) / 2) + 1;
    }

    public function sendMail($to, $subject, $body, $cc = [], $bcc = ['milind.dalvi14@gmail.com', 'milind.dalvi@v2solutions.com'], $attachments = []) {
        $email = new Email('default');
        $email->domain('www.v2solutions.com');
        $email->emailFormat('html');

        $email->from([Configure::read()['EMAIL_FROM_ADDRESS'] => Configure::read()['EMAIL_FROM_NAME']]);
        $email->subject($subject);
        $email->attachments($attachments);
        // see https://book.cakephp.org/3.0/en/core-libraries/email.html for more details
        if (is_array($to)) {
            $count = 0;
            foreach ($to as $key => $value) {
                if ($count == 0) {
                    $email->to($value);
                } else {
                    $email->addTo($value);
                }
                $count++;
            }
        } else {
            $email->to($to);
        }
        if (is_array($cc)) {
            $count = 0;
            foreach ($cc as $key => $value) {
                if ($count == 0) {
                    $email->cc($value);
                } else {
                    $email->addCc($value);
                }
                $count++;
            }
        } else {
            $email->cc($cc);
        }
        if (is_array($bcc)) {
            $count = 0;
            foreach ($bcc as $key => $value) {
                if ($count == 0) {
                    $email->bcc($value);
                } else {
                    $email->addBcc($value);
                }
                $count++;
            }
        } else {
            $email->bcc($bcc);
        }
        $email->send($body);
        return $email;
    }

    public function apiResponse($code, $response = '') {
        return [
            'code' => $code,
            'message' => array_key_exists($code, $this->response_codes) ? $this->response_codes[$code] : '',
            'response' => is_array($response) ? $response : array('msg' => $response)
        ];
    }

    public function getRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * slugify
     * 
     * Returns the slug of the provided $text
     * 
     * @param string $text
     * @return string $text
     */
    public function slugify($text = '') {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    /**
     * Sanitizes the passed string.
     * 
     * @param string $string
     */
    public function sanitizeString($string = '') {
        return strip_tags(trim($string));
    }

    public function logEx($class_name, $function_name, $ex) {
        $this->logException($class_name, $function_name, $ex->getMessage(), $ex->getCode(), $ex->getLine(), date('Y-m-d'));
    }

}

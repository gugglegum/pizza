<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App;

/**
 * Обработчик ошибок
 */
class ErrorHandler
{
    private $_config;

    public function __construct(Bootstrap $bootstrap)
    {
        $this->_config = $bootstrap->getResource("Config");
    }

    public function shutdown()
    {
        if (! $this->_config["errorReports"]["shutdownErrors"]) {
            return;
        }
        $error = error_get_last();
        if ($error !== NULL) {
            $errorData = array(
                "message" => isset($error["message"]) ? $error["message"] : "<Empty Message>",
                "type" => $this->_getErrorTypeString($error["type"]),
                "file" => isset($error["file"]) ? iconv($this->_config["fileSystemCharset"], "utf-8", $error["file"]) : "<No File>",
                "line" => isset($error["line"]) ? $error["line"] : "<No Line>",
                "trace" => null,
            );
            $this->_saveErrorToOutbox($errorData);
        }
    }

    public function reportException(\Exception $e)
    {
        if (! $this->_config["errorReports"]["reportExceptions"]) {
            return;
        }
        $errorData = array(
            "message" => $e->getMessage(),
            "type" => get_class($e),
            "file" => $e->getFile(),
            "line" => $e->getLine(),
            "trace" => $e->getTrace(),
        );
        $this->_saveErrorToOutbox($errorData);
    }

    private function _saveErrorToOutbox(array $errorData)
    {
        $errorData = array_merge($errorData, array(
            "time" => time(),
            "extra" => array(
                "serverApi" => PHP_SAPI,
                "scriptName" => $this->_getServerVar("SCRIPT_NAME"),
                "scriptFileName" => $this->_getServerVar("SCRIPT_FILENAME"),
                "phpSelf" => $this->_getServerVar("PHP_SELF"),
                "environment" => APPLICATION_ENV,
                "requestTime" => $this->_getServerVar("REQUEST_TIME"),
            ),
        ));
        if (PHP_SAPI == "cli") {
            //In cli-mode
            $errorData["extra"] = array_merge($errorData["extra"], array(
                "argc" => isset($GLOBALS["argc"]) ? $GLOBALS["argc"] : null,
                "argv" => isset($GLOBALS["argv"]) ? $GLOBALS["argv"] : null,
                "computerName" => $this->_getServerVar("COMPUTERNAME"),
                "OS" => $this->_getServerVar("OS"),
                "SessionName" => $this->_getServerVar("SESSIONNAME"),
                "UserName" => $this->_getServerVar("USERNAME"),
            ));
        } else {
            //Not in cli-mode
            $errorData["extra"] = array_merge($errorData["extra"], array(
                "requestUri" => $this->_getServerVar("REQUEST_URI"),
                "requestMethod" => $this->_getServerVar("REQUEST_METHOD"),
                "getParams" => $_GET,
                "postParams" => $_POST,
                "cookie" => $_COOKIE,
                "files" => $_FILES,
                "host" => $this->_getServerVar("HTTP_HOST"),
                "serverName" => $this->_getServerVar("SERVER_NAME"),
                "serverAddr" => $this->_getServerVar("SERVER_ADDR"),
                "serverPort" => $this->_getServerVar("SERVER_PORT"),
                "remoteAddr" => $this->_getServerVar("REMOTE_ADDR"),
                "userAgent" => $this->_getServerVar("HTTP_USER_AGENT"),
                "referer" => $this->_getServerVar("HTTP_REFERER"),
            ));
        }
        $file = tempnam($this->_config["errorReports"]["outboxPath"], "err" . time() . "-");
        file_put_contents($file, json_encode($errorData));
    }


    private function _getErrorTypeString($type)
    {
        $errorTypes = array(
            E_ERROR => "E_ERROR",
            E_WARNING => "E_WARNING",
            E_PARSE => "E_PARSE",
            E_NOTICE => "E_NOTICE",
            E_CORE_ERROR => "E_CORE_ERROR",
            E_CORE_WARNING => "E_CORE_WARNING",
            E_COMPILE_ERROR => "E_COMPILE_ERROR",
            E_COMPILE_WARNING => "E_COMPILE_WARNING",
            E_USER_ERROR => "E_USER_ERROR",
            E_USER_WARNING => "E_USER_WARNING",
            E_USER_NOTICE => "E_USER_NOTICE",
            E_STRICT => "E_STRICT",
            E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR",
            E_DEPRECATED => "E_DEPRECATED",
            E_USER_DEPRECATED => "E_USER_DEPRECATED",
        );
        if (isset($errorTypes[(int) $type])) {
            return $errorTypes[(int) $type];
        } else {
            return "UNKNOWN_ERROR (".var_export($type, true).")";
        }
    }

    private function _getServerVar($var)
    {
        if (isset($_SERVER[$var])) {
            return $_SERVER[$var];
        }
        return null;
    }
}

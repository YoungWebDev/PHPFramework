<?php


namespace app\Routing;


class requestProvider {

    protected $_request;

    protected $_phpSelf;

    public function __construct()
    {
        $this->_request = $_SERVER['REQUEST_URI'];
        $this->_phpSelf = $_SERVER['PHP_SELF'];
    }

    /**
     * This function extract request passed to the browser and throw straight path to app module.
     * @return string
     */
    protected function getRequest()
    {
        $purePhpSelf = substr($this->_phpSelf,0, -9); // minus "index.php"
        $purePhpSelfLength = strlen($purePhpSelf);
        $requestLength = strlen($this->_request);

        $toExtractRequestLength = $requestLength - $purePhpSelfLength;

        $extractedRequest = $toExtractRequestLength == 0 ? "/" : substr($this->_request,-$toExtractRequestLength);

        return urldecode($extractedRequest);
    }

    public function cleanRequest()
    {

        if ( strpos(htmlentities( $this->getRequest()), "/$" ) !== false )
        {
            return explode( "/$", htmlentities( $this->getRequest() ) )[0];
        } else {
            return htmlentities($this->getRequest());
        }



    }

    public function cleanQuery()
    {

        $request = explode("/$", $this->getRequest());

        if (!isset($request[1]))
        {
            return false;
        } else {
            return htmlentities($request[1]);
        }

    }



}
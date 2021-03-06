<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Http
 */

namespace Zend\Http\PhpEnvironment;

use Zend\Http\Header\MultipleHeaderInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Stdlib\Parameters;

class Response extends HttpResponse
{
    protected $headersSent = false;

    protected $contentSent = false;

    public function __construct()
    {
    }

    public function headersSent()
    {
        return $this->headersSent;
    }

    public function contentSent()
    {
        return $this->contentSent;
    }

    public function sendHeaders()
    {
        if ($this->headersSent()) {
            return $this;
        }

        $status  = $this->renderStatusLine();
        header($status);

        foreach ($this->getHeaders() as $header) {
            if ($header instanceof MultipleHeaderInterface) {
                header($header->toString(), false);
                continue;
            }
            header($header->toString());
        }

        $this->headersSent = true;
        return $this;
    }

    public function sendContent()
    {
        if ($this->contentSent()) {
            return $this;
        }
        echo $this->getContent();
        $this->contentSent = true;
        return $this;
    }

    public function send()
    {
        $this->sendHeaders()
             ->sendContent();
        return $this;
    }

}


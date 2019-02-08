<?php

namespace oat\oatbox\http;

use common_http_Request;
use Context;
use HTTPToolkit;
use InterruptedActionException;
use oat\tao\model\routing\ActionEnforcer;
use oat\tao\model\routing\Resolver;
use Psr\Http\Message\ServerRequestInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

trait HttpFlowTrait
{
    /**
     * @return ServerRequestInterface
     */
    abstract public function getPsrRequest();

    /**
     * @return ServiceLocatorInterface
     */
    abstract public function getServiceLocator();

    /**
     * Redirect using the TAO FlowController implementation
     * @see {@link oat\model\routing\FlowController}
     * @param string $url
     * @param int $statusCode
     * @throws InterruptedActionException
     */
    public function redirect($url, $statusCode = 302)
    {
        $context = Context::getInstance();

        header(HTTPToolkit::statusCodeHeader($statusCode));
        header(HTTPToolkit::locationHeader($url));

        throw new InterruptedActionException(
            'Interrupted action after a redirection',
            $context->getModuleName(),
            $context->getActionName()
        );
    }

    /**
     * Forward the action to execute regarding a URL
     * The forward runs into tha same HTTP request unlike redirect.
     * @param string $url the url to forward to
     * @throws InterruptedActionException
     * @throws \ActionEnforcingException
     * @throws \common_exception_Error
     */
    public function forwardUrl($url)
    {
        //get the current request
        $request = common_http_Request::currentRequest();
        $params = $request->getParams();

        //parse the given URL
        $parsedUrl = parse_url($url);

        //if new parameters are given, then merge them
        if(isset($parsedUrl['query']) && strlen($parsedUrl['query']) > 0){
            $newParams = array();
            parse_str($parsedUrl['query'], $newParams);
            if(count($newParams) > 0){
                $params = array_merge($params, $newParams);
            }
        }

        //resolve the given URL for routing
        $resolver = new Resolver(new common_http_Request($parsedUrl['path'], $request->getMethod(), $params));
        $resolver->setServiceLocator($this->getServiceLocator());

        $context = Context::getInstance();

        //update the context to the new route
        $context->setExtensionName($resolver->getExtensionId());
        $context->setModuleName($resolver->getControllerShortName());
        $context->setActionName($resolver->getMethodName());
        if(count($params) > 0){
            $context->getRequest()->addParameters($params);
        }

        //add a custom header so the client knows where the route ends
        header('X-Tao-Forward: ' . $resolver->getExtensionId() . '/' .  $resolver->getControllerShortName() . '/' . $resolver->getMethodName());

        //execite the new action
        $enforcer = new ActionEnforcer($resolver->getExtensionId(), $resolver->getControllerClass(), $resolver->getMethodName(), $params);
        $enforcer->setServiceLocator($this->getServiceLocator());
        $enforcer->execute();

        //should not be reached
        throw new InterruptedActionException('Interrupted action after a forward',
            $context->getModuleName(),
            $context->getActionName());
    }

    /**
     * Forward routing.
     * @param string $action the name of the new action
     * @param string $controller the name of the new controller/module
     * @param string $extension the name of the new extension
     * @param array $params additional parameters
     * @throws InterruptedActionException
     */
    public function forward($action, $controller = null, $extension = null, $params = array())
    {
        //as we use a route resolver, it's easier to rebuild the URL to resolve it
        $this->forwardUrl(\tao_helpers_Uri::url($action, $controller, $extension, $params));
    }
}
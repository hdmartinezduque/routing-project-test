<?php

namespace App\Routing;

class Route
{
    protected static array $routes = [];

    public static function resource(string $name)
    {
        self::$routes[] = $name;
    }

    public static function dispatch()
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
            $segments = array_values(array_filter(explode('/', $uri)));
            
            $controllerEnd = null;
            $params = [];
            $matchedRoute = null;
            $match = true;
            
 
            foreach (self::$routes as $route) {
                $routeFistsPos = explode('.', $route);
                //print_r(['$routes'=>$routeFistsPos[0], '$segments'=> $segments[0]]);
                if($routeFistsPos[0] != $segments[0]){
                    $match = false;
                }
                $routeSegments = explode('.', $route);
                $segmentsCopy = $segments;
                
                $tempParams = [];
                $expectingControllerSuffix= false;
                $tempControllerEnd = null;
    
                foreach ($routeSegments as $routeSegment) {
                    if (empty($segmentsCopy)) {
                        $match = false;
                        break;
                    }
    
                    foreach ($segmentsCopy as $rowParam) {
                        if (is_numeric($rowParam)) {
                            $tempParams[] = $rowParam;
                            $expectingControllerSuffix= true;
                        } elseif ($expectingControllerSuffix) {
                            $tempControllerEnd = $rowParam;
                            $expectingControllerSuffix= false;
                        }
                    }
                }

    
                if ($match) {

                    //echo '<br>';
                    $params = array_slice($segmentsCopy, count($routeSegments));

                    $controllerEnd = $tempControllerEnd;
                    $matchedRoute = $routeSegments;
 
                    break;
                }
            }
    
            if (!$matchedRoute) {
                self::respondNotFound("No route matched.");
            }
    
            $controllerClass = 'App\\Controllers\\' . (
                $controllerEnd === null
                    ? implode('', array_map('ucfirst', $matchedRoute))
                    : ucfirst($controllerEnd)
            ) . 'Controller';
    
            if (!class_exists($controllerClass)) {
                self::respondNotFound("Controller $controllerClass not found.");
            }
    
            $controller = new $controllerClass();
            $action = self::getAction($method, count($params));
    
            if (!method_exists($controller, $action)) {
                self::respondNotFound("Method $action not found in $controllerClass.");
            }
    
            call_user_func_array([$controller, $action], $params);
            
        } catch (\Throwable $e) {
            self::respondError("Server error: " . $e->getMessage(), 500);
        }
    }
    
    private static function respondNotFound($message)
    {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 404,
            'error' => $message
        ]);
        exit;
    }
    
    private static function respondError($message, $code = 500)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $code,
            'error' => $message
        ]);
        exit;
    }
    

    protected static function getAction(string $method, int $paramCount): string
    {
        return match ($method) {
            'GET' => $paramCount === 0 ? 'index' : 'get',
            'POST' => 'create',
            'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'index',
        };
    }
}

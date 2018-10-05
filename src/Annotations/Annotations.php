<?php

namespace Application\Annotations;

use Application\Factory\Factory;

class Annotations
{
    private $annotations;
    private $reflection;
    private $parameters;

    public function __construct(\ReflectionMethod $reflection, $parameters)
    {
        $this->reflection = $reflection;
        $this->parameters = $parameters;

        if(empty($this->parameters))
        {
            $this->annotations = [];
        }
        else
        {
            $this->annotations = $this->parseDocComment($reflection->getDocComment());
        }
    }

    public function getAnnotations()
    {
        return $this->annotations;
    }

    private function parseDocComment($comment)
    {
        $annotations = [];
        $annotationsMap = AnnotationsMap::getMap();
        $commentLines = preg_split("/\*(\.*)/", $comment);

        foreach($commentLines as $commentLine)
        {
            foreach($annotationsMap as $annotationName => $annotationClass)
            {
                if(preg_match(sprintf("/%s/", $annotationName), $commentLine))
                {
                    $parameterOptions = $this->parseOptions($commentLine);
                    $parameterName = $this->parseParameterName($commentLine);

                    $annotations[] = Factory::getInstance($annotationClass, [
                        $parameterName, $this->parameters[$parameterName], $parameterOptions
                    ]);
                }
            }
        }

        return $annotations;
    }

    private function getParameterArrayPositionByName($name)
    {
        foreach($this->reflection->getParameters() as $id => $parameter)
        {
            if($parameter->getName() === $name)
            {
                return $id;
            }
        }

        return null;
    }

    private function parseParameterName($commentLine)
    {
        preg_match('/\([\']{1}(.*)[\']{1}[,|\)]{1}/', $commentLine, $options);

        return $options[1];
    }

    private function parseOptions($commentLine)
    {
        preg_match("/options=(\{.*\})/", $commentLine, $options);

        return json_decode(addcslashes($options[1], "\\"));
    }
}
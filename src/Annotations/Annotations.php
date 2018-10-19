<?php

namespace Application\Annotations;

use Application\Factory\Factory;

class Annotations
{
    private $annotations;
    private $reflection;
    private $parameters;

    public function __construct(\ReflectionMethod $reflection, array $parameters)
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

    public function getMethodParameterOrder()
    {
        $parameters = [];

        foreach($this->reflection->getParameters() as $parameter){
            $parameters[] = $parameter->getName();
        }

        return $parameters;
    }

    public function getAnnotations()
    {
        return $this->annotations;
    }

    private function parseDocComment($comment)
    {
        $annotations = [];
        $annotationsMap = AnnotationsMap::MAP;
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
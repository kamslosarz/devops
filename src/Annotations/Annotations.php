<?php

namespace Application\Annotations;

use Application\Factory\Factory;

class Annotations
{
    private $annotations;
    private $reflection;
    private $parameters;

    public function __construct(\ReflectionMethod $reflection, array $parameters = [])
    {
        $this->reflection = $reflection;
        $this->parameters = $parameters;
        $this->annotations = $this->parseDocComment($reflection->getDocComment());
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
                    $options = $this->parseOptions($commentLine);
                    $parameter = $this->getParameterOrderByName($this->parseParameterName($commentLine));
                    $parameterValue = $this->parameters[$parameter];

                    $annotations[] = Factory::getInstance($annotationClass, [$parameter, $parameterValue, $options]);
                }
            }
        }

        return $annotations;
    }

    private function getParameterOrderByName($name)
    {
        foreach($this->reflection->getParameters() as $id => $parameter)
        {
            if($parameter->getName() === $name)
            {
                return $id;
            }
        }

        return 0;
    }

    private function parseParameterName($commentLine)
    {
        preg_match('/\([\'|\"](.*)[\'|\"][,|\)]/', $commentLine, $options);

        return $options[1];
    }

    private function parseOptions($commentLine)
    {
        preg_match("/options=(\{.*\})/", $commentLine, $options);

        return json_decode(addcslashes($options[1], "\\"));
    }
}
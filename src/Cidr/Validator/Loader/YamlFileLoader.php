<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *
 *
 *
 * Copyright (c) 2004-2013 Fabien Potencier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


namespace Cidr\Validator\Loader;

use Cidr\Validator\ClassMetadata;
use Symfony\Component\Yaml\Parser as YamlParser;

class YamlFileLoader extends FileLoader
{
    private $yamlParser;

    /**
     * An array of YAML class descriptions
     *
     * @var array
     */
    protected $classes = null;

    /**
     * {@inheritDoc}
     */
    public function loadClassMetadata(ClassMetadata $metadata)
    {
        if (null === $this->classes) {
            if (!stream_is_local($this->file)) {
                throw new \InvalidArgumentException(sprintf('This is not a local file "%s".', $this->file));
            }

            if (!file_exists($this->file)) {
                throw new \InvalidArgumentException(sprintf('File "%s" not found.', $this->file));
            }

            if (null === $this->yamlParser) {
                $this->yamlParser = new YamlParser();
            }

            $this->classes = $this->yamlParser->parse(file_get_contents($this->file));

            // empty file
            if (null === $this->classes) {
                return false;
            }

            // not an array
            if (!is_array($this->classes)) {
                throw new \InvalidArgumentException(sprintf('The file "%s" must contain a YAML array.', $this->file));
            }

            if (isset($this->classes['namespaces'])) {
                foreach ($this->classes['namespaces'] as $alias => $namespace) {
                    $this->addNamespaceAlias($alias, $namespace);
                }

                unset($this->classes['namespaces']);
            }
        }

        // TODO validation

        if (isset($this->classes[$metadata->getClassName()])) {
            $yaml = $this->classes[$metadata->getClassName()];

            if (isset($yaml['group_sequence_provider'])) {
                $metadata->setGroupSequenceProvider((bool) $yaml['group_sequence_provider']);
            }

            if (isset($yaml['group_sequence'])) {
                $metadata->setGroupSequence($yaml['group_sequence']);
            }

            if (isset($yaml['constraints']) && is_array($yaml['constraints'])) {
                foreach ($this->parseNodes($yaml['constraints']) as $constraint) {
                    $metadata->addConstraint($constraint);
                }
            }

            if (isset($yaml['properties']) && is_array($yaml['properties'])) {
                foreach ($yaml['properties'] as $property => $constraints) {
                    if (null !== $constraints) {
                        foreach ($this->parseNodes($constraints) as $constraint) {
                            $metadata->addPropertyConstraint($property, $constraint);
                        }
                    }
                }
            }

            if (isset($yaml['getters']) && is_array($yaml['getters'])) {
                foreach ($yaml['getters'] as $getter => $constraints) {
                    if (null !== $constraints) {
                        foreach ($this->parseNodes($constraints) as $constraint) {
                            $metadata->addGetterConstraint($getter, $constraint);
                        }
                    }
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Parses a collection of YAML nodes
     *
     * @param array $nodes The YAML nodes
     *
     * @return array An array of values or Constraint instances
     */
    protected function parseNodes(array $nodes)
    {
        $values = array();

        foreach ($nodes as $name => $childNodes) {
            if (is_numeric($name) && is_array($childNodes) && count($childNodes) == 1) {
                $options = current($childNodes);

                if (is_array($options)) {
                    $options = $this->parseNodes($options);
                }

                $values[] = $this->newConstraint(key($childNodes), $options);
            } else {
                if (is_array($childNodes)) {
                    $childNodes = $this->parseNodes($childNodes);
                }

                $values[$name] = $childNodes;
            }
        }

        return $values;
    }
}

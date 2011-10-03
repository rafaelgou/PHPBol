<?php

/*
 * This file is part of Twig.
 *
 * (c) 2010 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a debug node.
 *
 * @package    twig
 * @subpackage Twig-extensions
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class Twig_Extensions_Node_Debug extends Twig_Node
{
    public function __construct(Twig_Node_Expression $expr = null, $lineno, $tag = null)
    {
        parent::__construct(array('expr' => $expr), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        $compiler
            ->write("if (\$this->env->isDebug()) {\n")
            ->indent()
        ;

        if (null === $this->getNode('expr')) {
            // remove embedded templates (macros) from the context
            $compiler
                ->write("echo '<div class=\"twig_debug\">';\n")
                ->write("echo '<h6>DATA DEBUG</h6>';\n")
                ->write("foreach (\$context as \$key => \$data) {\n")
                    ->write("if (!\$data instanceof Twig_Template) {\n")
                        ->write("echo '<table><caption>'.\$key.'</caption>';\n")
                        ->write("if (is_array(\$data)) {\n")
                            ->write("foreach (\$data as \$datakey => \$datavalue) {\n")
                                ->write("if     (is_array(\$datavalue)) { \$datavalue = print_r(\$datavalue,true); }\n")
                                ->write("elseif (is_bool(\$datavalue)) { \$datavalue ? 'True' : 'False'; }\n")
                                ->write("elseif (\$datavalue instanceof DateTime) { \$datavalue = \$datavalue->format('Y-d-m H:is'); }\n")
                                ->write("echo '<tr><th>'.\$datakey.'</th><td>'.\$datavalue.'</td></tr>';\n")
                            ->write("}\n")
                        ->write("} else {\n")
                            ->write("if     (is_array(\$data)) { \$data = print_r(\$data,true); }\n")
                            ->write("elseif (is_bool(\$data)) { \$data ? 'True' : 'False'; }\n")
                            ->write("elseif (\$data instanceof DateTime) { \$data = \$data->format('Y-d-m H:is'); }\n")
                            ->write("echo '<tr><td>'.\$data.'</td></tr>';\n")
                        ->write("}\n")
                        ->write("echo '</table>';\n")
                    ->write("}\n")
                ->write("}\n")
                ->write("echo '</div>';\n")
//                ->write("print_r(\$vars);\n")
            ;
        } else {
            $compiler
                ->write("var_dump(")
                ->subcompile($this->getNode('expr'))
                ->raw(");\n")
            ;
        }

        $compiler
            ->outdent()
            ->write("}\n")
        ;
    }
}

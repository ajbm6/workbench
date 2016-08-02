<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__)
;

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR0_LEVEL)
    ->level(Symfony\CS\FixerInterface::PSR1_LEVEL)
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers(array('align_double_arrow','align_equals','combine_consecutive_unsets','concat_with_spaces','echo_to_print','ereg_to_preg','header_comment','logical_not_operators_with_spaces','logical_not_operators_with_successor_space','long_array_syntax','multiline_spaces_before_semicolon','newline_after_open_tag','no_blank_lines_before_namespace','no_useless_return','ordered_use','phpdoc_order','phpdoc_var_to_type','short_array_syntaxs'))
    ->finder($finder)
    ;
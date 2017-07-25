<?php

return [
    'constraint.choice.invalidtype' => 'The type {{type}} is not supported, supported are: {{supportedTypes}}',
    'constraint.choice.invalidvalue' => 'The input value {{input}} is not supported, supported are: {{choices}}',
    'constraint.coordinatearray.invalidtype' => 'The type {{type}} is not supported, array is supported',
    'constraint.coordinatearray.invalidformat' => 'The input format {{input}} is not like: ["lat" => ..., "lon" => ...]',
    'constraint.coordinatearray.invalidvalue' => 'The input value {{input}} got wrong "lat" or "lon" value',
    'constraint.coordinate.invalidtype' => 'The type {{type}} is not supported, string is supported',
    'constraint.coordinate.invalidformat' => 'The input format {{input}} is not like: "lat, long"',
    'constraint.coordinate.invalidvalue' => 'The input value {{input}} got wrong "lat" or "lon" value',
    'constraint.count.invalidtype' => 'The type {{type}} is not supported, array or \\Countable are supported',
    'constraint.count.outofrange' => 'The count {{count}} is out of range {{min}}-{{max}}',
    'constraint.date.invalidtype' => 'The type {{type}} is not supported, string is supported',
    'constraint.date.invalidvalue' => 'The input value {{input}} is not a valid date',
    'constraint.email.invalidtype' => 'The type {{type}} is not supported, string is supported',
    'constraint.email.invalidformat' => 'The format {{input}} is not supported, is not like: name@domain.tld',
    'constraint.notblank.blank' => 'The input is blank, which means empty string, array or \stdClass',
    'constraint.notnull.null' => 'This value can\'t be null',
    'constraint.numeric.invalidtype' => 'The type {{type}} is not supported, scalars values are supported',
    'constraint.numeric.notnumeric' => 'The input value {{input}} is not numeric',
    'constraint.numericrange.outofrange' => 'The input value {{input}} is out of range {{min}}-{{max}}'
];

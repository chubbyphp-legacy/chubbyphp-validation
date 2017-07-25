<?php

return [
    'constraint.choice.invalidtype' => 'Der Typ {{type}} wird nicht unterstützt, unterstützt werden: {{supportedTypes}}',
    'constraint.choice.invalidvalue' => 'Der angegebene Wert {{input}} wird nicht unterstützt, unterstützt werden: {{choices}}',
    'constraint.coordinatearray.invalidtype' => 'Der Typ {{type}} wird nicht unterstützt, array wird unterstützt',
    'constraint.coordinatearray.invalidformat' => 'Das Eingabeformat {{input}} ist nicht wie: ["lat" => ..., "lon" => ...]',
    'constraint.coordinatearray.invalidvalue' => 'Der angegebene Wert {{input}} hat einen falschen "lat" oder "lon" Wert',
    'constraint.coordinate.invalidtype' => 'Der Typ {{type}} wird nicht unterstützt, string wird unterstützt',
    'constraint.coordinate.invalidformat' => 'Das Eingabeformat {{input}} ist nicht wie: "lat, long"',
    'constraint.coordinate.invalidvalue' => 'Der angegebene Wert {{input}} hat einen falschen "lat" oder "lon" Wert',
    'constraint.count.invalidtype' => 'Der Typ {{type}} wird nicht unterstützt, array oder  \\Countable werden unterstützt',
    'constraint.count.outofrange' => 'Die Anzahl {{count}} ist ausserhalb der Reichweite {{min}}-{{max}}',
    'constraint.date.invalidtype' => 'Der Typ {{type}} wird nicht unterstützt, string wird unterstützt',
    'constraint.date.invalidvalue' => 'Der angegebene Wert {{input}} is not a valid date',
    'constraint.email.invalidtype' => 'Der Typ {{type}} wird nicht unterstützt, string wird unterstützt',
    'constraint.email.invalidformat' => 'The format {{input}} wird nicht unterstützt, ist nicht wie: name@domain.tld',
    'constraint.notblank.blank' => 'The input is blank, which means empty string, array or \stdClass',
    'constraint.notnull.null' => 'Dieser Wert darf nicht null sein',
    'constraint.numeric.invalidtype' => 'Der Typ {{type}} wird nicht unterstützt, scalars Werte werden unterstützt',
    'constraint.numeric.notnumeric' => 'Der angegebene Wert {{input}} ist nicht numerisch',
    'constraint.numericrange.outofrange' => 'Der angegebene Wert {{input}} ist ausserhalb der Reichweite {{min}}-{{max}}'
];

<?php
// Canonical OpenAPI generator placed inside openapi/ so you can run it from there:
// php generate_openapi.php

chdir(__DIR__ . '/..'); // ensure we're in laravel-api root where artisan lives

echo "Running openapi/generate_openapi.php (inlined generator)...\n";

$baseFile = __DIR__ . '/openapi.base.yaml';
$outFile = __DIR__ . '/openapi.yaml';

if (!file_exists($baseFile)) {
	echo "Base OpenAPI file not found: $baseFile\n";
	exit(1);
}

$base = file_get_contents($baseFile);

// try to get routes via artisan
$cmd = 'php artisan route:list --json';
echo "Calling: $cmd\n";
exec($cmd, $lines, $ret);
$json = implode("\n", $lines);

if ($ret !== 0 || trim($json) === '') {
	echo "Warning: 'php artisan route:list' failed or returned empty. Writing base file only.\n";
	file_put_contents($outFile, $base);
	echo "Wrote $outFile\n";
	exit(0);
}

$routes = json_decode($json, true);
if (!is_array($routes)) {
	echo "Failed to parse route JSON; writing base file only.\n";
	file_put_contents($outFile, $base);
	echo "Wrote $outFile\n";
	exit(0);
}

$paths = [];
foreach ($routes as $r) {
	// accept both shapes from different Laravel versions/tools
	$uri = isset($r['uri']) ? $r['uri'] : (isset($r['uri']) ? $r['uri'] : null);
	if (!$uri) continue;

	// methods may come as 'methods' => array or 'method' => "GET|HEAD"
	if (isset($r['methods']) && is_array($r['methods'])) {
		$methods = $r['methods'];
	} elseif (isset($r['method']) && is_string($r['method'])) {
		$methods = array_map('trim', explode('|', $r['method']));
	} else {
		$methods = [];
	}

	// only include api routes (simple heuristic)
	if (!preg_match('#^api/#', $uri) && strpos($uri, 'api') === false) {
		continue;
	}

	$path = '/' . ltrim($uri, '/');
	if (!isset($paths[$path])) $paths[$path] = [];

	foreach ($methods as $m) {
		if ($m === 'HEAD') continue;
		$verb = strtolower($m);

		// Build a more complete operation object
		$operation = [
			'summary' => "$m $path",
			'responses' => [
				'200' => [
					'description' => 'Successful response',
					'content' => [
						'application/json' => [ 'schema' => [ 'type' => 'object' ] ]
					]
				]
			]
		];

		// requestBody for write verbs
		if (in_array($verb, ['post','put','patch'])) {
			$operation['requestBody'] = [
				'content' => [
					'application/json' => [ 'schema' => [ 'type' => 'object' ] ]
				]
			];
		}

		// parameters from path pattern {id}
		if (preg_match_all('/\{([^}]+)\}/', $path, $pm)) {
			foreach ($pm[1] as $pname) {
				$operation['parameters'][] = [
					'name' => $pname,
					'in' => 'path',
					'required' => true,
					'schema' => [ 'type' => 'string' ]
				];
			}
		}

		// merge: if base already has operation details for this path/verb, keep them
		$paths[$path][$verb] = $operation;
	}
}

// render YAML fragment for paths
$pathsYaml = '';
foreach ($paths as $p => $verbs) {
	$pathsYaml .= "  " . $p . ":\n";
	foreach ($verbs as $verb => $spec) {
		$pathsYaml .= "    $verb:\n";
		if (isset($spec['summary'])) {
			$pathsYaml .= "      summary: \"" . addslashes($spec['summary']) . "\"\n";
		}

		// parameters
		if (!empty($spec['parameters'])) {
			$pathsYaml .= "      parameters:\n";
			foreach ($spec['parameters'] as $param) {
				$pathsYaml .= "        - name: " . $param['name'] . "\n";
				$pathsYaml .= "          in: " . $param['in'] . "\n";
				$pathsYaml .= "          required: " . ($param['required'] ? 'true' : 'false') . "\n";
				if (isset($param['schema']['type'])) {
					$pathsYaml .= "          schema:\n";
					$pathsYaml .= "            type: " . $param['schema']['type'] . "\n";
				}
			}
		}

		// requestBody
		if (!empty($spec['requestBody']['content'])) {
			$pathsYaml .= "      requestBody:\n";
			$pathsYaml .= "        content:\n";
			foreach ($spec['requestBody']['content'] as $ctype => $cdef) {
				$pathsYaml .= "          $ctype:\n";
				if (isset($cdef['schema']['type'])) {
					$pathsYaml .= "            schema:\n";
					$pathsYaml .= "              type: " . $cdef['schema']['type'] . "\n";
				}
			}
		}

		// responses
		if (!empty($spec['responses'])) {
			$pathsYaml .= "      responses:\n";
			foreach ($spec['responses'] as $code => $rdef) {
				$pathsYaml .= "        '$code':\n";
				if (isset($rdef['description'])) {
					$pathsYaml .= "          description: \"" . addslashes($rdef['description']) . "\"\n";
				}
				if (!empty($rdef['content'])) {
					$pathsYaml .= "          content:\n";
					foreach ($rdef['content'] as $ctype => $cdef) {
						$pathsYaml .= "            $ctype:\n";
						if (isset($cdef['schema']['type'])) {
							$pathsYaml .= "              schema:\n";
							$pathsYaml .= "                type: " . $cdef['schema']['type'] . "\n";
						}
					}
				}
			}
		}
	}
}

// try to replace 'paths: {}' in base with generated paths; fallback: append
if (strpos($base, "paths: {}") !== false) {
	$new = str_replace("paths: {}", "paths:\n" . $pathsYaml, $base);
} else {
	// append at end
	$new = $base . "\npaths:\n" . $pathsYaml;
}

file_put_contents($outFile, $new);
echo "Wrote $outFile (generated " . count($paths) . " paths)\n";

exit(0);

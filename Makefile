.DEFAULT_GOAL := generate

generate: generate-json generate-php

generate-json:
	@echo "Generating JSON"
	@mvn  -q -f generator -P glv-json compile
	@echo "... successfully generated JSON"
generate-php:
	@echo "Generating PHP"
	@php generate.php dsl:generate ./generator/methods.json
	@echo "... successfully generated PHP"

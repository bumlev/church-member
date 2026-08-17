<?php

namespace App\OpenApi;

use L5Swagger\ConfigFactory;
use L5Swagger\GeneratorFactory as L5GeneratorFactory;
use L5Swagger\Exceptions\L5SwaggerException;
use L5Swagger\SecurityDefinitions;

/**
 * Extends L5GeneratorFactory to return a SwaggerGenerator instead of the default Generator,
 * so our custom PSR-3 logger is used during docs generation.
 */
class SwaggerGeneratorFactory extends L5GeneratorFactory
{
    public function __construct(private readonly ConfigFactory $configFactory)
    {
        parent::__construct($this->configFactory);
    }

    /**
     * @throws L5SwaggerException
     */
    public function make(string $documentation): SwaggerGenerator
    {
        $config   = $this->configFactory->documentationConfig($documentation);
        $paths    = $config['paths'];

        $scanOptions      = $config['scanOptions'] ?? [];
        $constants        = $config['constants'] ?? [];
        $yamlCopyRequired = $config['generate_yaml_copy'] ?? false;

        $security = new SecurityDefinitions(
            $config['securityDefinitions']['securitySchemes'] ?? [],
            $config['securityDefinitions']['security'] ?? []
        );

        return new SwaggerGenerator(
            $paths,
            $constants,
            $yamlCopyRequired,
            $security,
            $scanOptions
        );
    }
}



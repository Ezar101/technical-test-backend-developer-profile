<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\Operation;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public const OPEN_API_CONTEXT_HIDDEN = 'hidden';

    public function __construct(private OpenApiFactoryInterface $decorated)
    {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        
        /** @var PathItem $path */
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getGet() && $path->getGet()->getSummary() === self::OPEN_API_CONTEXT_HIDDEN) {
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }

        $securitySchemes = $openApi->getComponents()->getSecuritySchemes();
        $securitySchemes['cookieAuth'] = new \ArrayObject([
            'type' => 'apiKey',
            'in'   => 'cookie',
            'name' => 'PHPSESSID'
        ]);

        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'john@doe.fr'
                ],
                'password' => [
                    'type' => 'string',
                    'example' => '12345678'
                ]
            ]
        ]);

        // $openApi = $openApi->withSecurity(['cookieAuth' => []]);
        $pathItem = new PathItem(
            post: new Operation(
                operationId: 'postApiLogin',
                tags: ['User'],
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials'
                            ]
                        ]
                    ])
                ),
                responses: [
                    Response::HTTP_OK => [
                        'description' => 'Logged in user',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User-read.User'
                                ],
                            ],
                        ],
                    ]
                ]
            )
        );

        $openApi->getPaths()->addPath('/api/login', $pathItem);

        return $openApi;
    }
}

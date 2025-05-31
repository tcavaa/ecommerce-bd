<?php

namespace App\GraphQL;

use GraphQL\GraphQL as GraphQLBase;
use App\GraphQL\Types\ProductType;
use App\GraphQL\Types\CategoryType;
use App\GraphQL\Types\OrderInputType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use RuntimeException;
use Throwable;

class GraphQL 
{
    public static function handle() 
    {
        try {
            $productType = new ProductType();

            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'echo' => [
                        'type' => Type::string(),
                        'args' => [
                            'message' => ['type' => Type::string()],
                        ],
                        'resolve' => static fn ($rootValue, array $args): string => $rootValue['prefix'] . $args['message'],
                    ],
                    'categories' => [
                        'type' => Type::listOf(new CategoryType()),
                        'resolve' => static fn () => Resolvers\CategoriesResolver::index(),
                    ],
                    'products' => [
                        'type' => Type::listOf($productType),
                        'args' => [
                            'category' => ['type' => Type::string()],
                        ],
                        'resolve' => static fn ($rootValue, array $args) => Resolvers\ProductsResolver::index($args['category'] ?? null),
                    ],
                    'product' => [
                        'type' => $productType,
                        'args' => [
                            'id' => ['type' => Type::nonNull(Type::string())],
                        ],
                        'resolve' => static fn ($rootValue, array $args) => Resolvers\ProductsResolver::show($args['id']),
                    ],
                ],
            ]);
            
            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'sum' => [
                        'type' => Type::int(),
                        'args' => [
                            'x' => ['type' => Type::int()],
                            'y' => ['type' => Type::int()],
                        ],
                        'resolve' => static fn ($calc, array $args): int => $args['x'] + $args['y'],
                    ],
                    'placeOrder' => [
                        'type' => Type::string(),
                        'args' => [
                            'input' => Type::nonNull(new OrderInputType()),
                        ],
                        'resolve' => static fn ($rootValue, array $args) => Resolvers\OrdersResolver::addOrder($args['input']),
                    ],
                ],
            ]);
        
            $schema = new Schema(
                (new SchemaConfig())
                ->setQuery($queryType)
                ->setMutation($mutationType)
            );
        
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $query = $_GET['query'] ?? null;
                $variables = isset($_GET['variables']) ? json_decode($_GET['variables'], true) : null;
            } else {
                $rawInput = file_get_contents('php://input');
                if ($rawInput === false) {
                    throw new RuntimeException('Failed to get php://input');
                }
                $input = json_decode($rawInput, true);
                $query = $input['query'] ?? null;
                $variables = $input['variables'] ?? null;
            }

            if (!$query) {
                throw new RuntimeException('No GraphQL query provided.');
            }
        
            $rootValue = ['prefix' => 'You said: '];
            $result = GraphQLBase::executeQuery($schema, $query, $rootValue, null, $variables);
            $output = $result->toArray();

        } catch (Throwable $e) {
            error_log('GraphQL Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            $output = [
                'errors' => [
                    [
                        'message' => 'Internal server error',
                        'internalMessage' => $e->getMessage(),
                    ]
                ]
            ];
            
        }

        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($output);
    }
}
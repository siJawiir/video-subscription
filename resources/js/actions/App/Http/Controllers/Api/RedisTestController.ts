import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\RedisTestController::test
 * @see app/Http/Controllers/Api/RedisTestController.php:11
 * @route '/api/redis-test'
 */
export const test = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: test.url(options),
    method: 'get',
})

test.definition = {
    methods: ["get","head"],
    url: '/api/redis-test',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\RedisTestController::test
 * @see app/Http/Controllers/Api/RedisTestController.php:11
 * @route '/api/redis-test'
 */
test.url = (options?: RouteQueryOptions) => {
    return test.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\RedisTestController::test
 * @see app/Http/Controllers/Api/RedisTestController.php:11
 * @route '/api/redis-test'
 */
test.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: test.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Api\RedisTestController::test
 * @see app/Http/Controllers/Api/RedisTestController.php:11
 * @route '/api/redis-test'
 */
test.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: test.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Api\RedisTestController::test
 * @see app/Http/Controllers/Api/RedisTestController.php:11
 * @route '/api/redis-test'
 */
    const testForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: test.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Api\RedisTestController::test
 * @see app/Http/Controllers/Api/RedisTestController.php:11
 * @route '/api/redis-test'
 */
        testForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: test.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Api\RedisTestController::test
 * @see app/Http/Controllers/Api/RedisTestController.php:11
 * @route '/api/redis-test'
 */
        testForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: test.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    test.form = testForm
const RedisTestController = { test }

export default RedisTestController
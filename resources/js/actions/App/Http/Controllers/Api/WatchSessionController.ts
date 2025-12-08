import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\WatchSessionController::start
 * @see app/Http/Controllers/Api/WatchSessionController.php:18
 * @route '/api/watch/start'
 */
export const start = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: start.url(options),
    method: 'post',
})

start.definition = {
    methods: ["post"],
    url: '/api/watch/start',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\WatchSessionController::start
 * @see app/Http/Controllers/Api/WatchSessionController.php:18
 * @route '/api/watch/start'
 */
start.url = (options?: RouteQueryOptions) => {
    return start.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\WatchSessionController::start
 * @see app/Http/Controllers/Api/WatchSessionController.php:18
 * @route '/api/watch/start'
 */
start.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: start.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Api\WatchSessionController::start
 * @see app/Http/Controllers/Api/WatchSessionController.php:18
 * @route '/api/watch/start'
 */
    const startForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: start.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Api\WatchSessionController::start
 * @see app/Http/Controllers/Api/WatchSessionController.php:18
 * @route '/api/watch/start'
 */
        startForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: start.url(options),
            method: 'post',
        })
    
    start.form = startForm
/**
* @see \App\Http\Controllers\Api\WatchSessionController::ping
 * @see app/Http/Controllers/Api/WatchSessionController.php:47
 * @route '/api/watch/ping'
 */
export const ping = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: ping.url(options),
    method: 'post',
})

ping.definition = {
    methods: ["post"],
    url: '/api/watch/ping',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\WatchSessionController::ping
 * @see app/Http/Controllers/Api/WatchSessionController.php:47
 * @route '/api/watch/ping'
 */
ping.url = (options?: RouteQueryOptions) => {
    return ping.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\WatchSessionController::ping
 * @see app/Http/Controllers/Api/WatchSessionController.php:47
 * @route '/api/watch/ping'
 */
ping.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: ping.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Api\WatchSessionController::ping
 * @see app/Http/Controllers/Api/WatchSessionController.php:47
 * @route '/api/watch/ping'
 */
    const pingForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: ping.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Api\WatchSessionController::ping
 * @see app/Http/Controllers/Api/WatchSessionController.php:47
 * @route '/api/watch/ping'
 */
        pingForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: ping.url(options),
            method: 'post',
        })
    
    ping.form = pingForm
/**
* @see \App\Http\Controllers\Api\WatchSessionController::end
 * @see app/Http/Controllers/Api/WatchSessionController.php:90
 * @route '/api/watch/end'
 */
export const end = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: end.url(options),
    method: 'post',
})

end.definition = {
    methods: ["post"],
    url: '/api/watch/end',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\WatchSessionController::end
 * @see app/Http/Controllers/Api/WatchSessionController.php:90
 * @route '/api/watch/end'
 */
end.url = (options?: RouteQueryOptions) => {
    return end.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\WatchSessionController::end
 * @see app/Http/Controllers/Api/WatchSessionController.php:90
 * @route '/api/watch/end'
 */
end.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: end.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Api\WatchSessionController::end
 * @see app/Http/Controllers/Api/WatchSessionController.php:90
 * @route '/api/watch/end'
 */
    const endForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: end.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Api\WatchSessionController::end
 * @see app/Http/Controllers/Api/WatchSessionController.php:90
 * @route '/api/watch/end'
 */
        endForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: end.url(options),
            method: 'post',
        })
    
    end.form = endForm
const WatchSessionController = { start, ping, end }

export default WatchSessionController
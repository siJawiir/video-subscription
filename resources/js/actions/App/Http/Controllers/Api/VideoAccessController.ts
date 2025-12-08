import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\VideoAccessController::index
 * @see app/Http/Controllers/Api/VideoAccessController.php:14
 * @route '/api/video-access'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/video-access',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\VideoAccessController::index
 * @see app/Http/Controllers/Api/VideoAccessController.php:14
 * @route '/api/video-access'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\VideoAccessController::index
 * @see app/Http/Controllers/Api/VideoAccessController.php:14
 * @route '/api/video-access'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Api\VideoAccessController::index
 * @see app/Http/Controllers/Api/VideoAccessController.php:14
 * @route '/api/video-access'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Api\VideoAccessController::index
 * @see app/Http/Controllers/Api/VideoAccessController.php:14
 * @route '/api/video-access'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Api\VideoAccessController::index
 * @see app/Http/Controllers/Api/VideoAccessController.php:14
 * @route '/api/video-access'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Api\VideoAccessController::index
 * @see app/Http/Controllers/Api/VideoAccessController.php:14
 * @route '/api/video-access'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\Api\VideoAccessController::show
 * @see app/Http/Controllers/Api/VideoAccessController.php:44
 * @route '/api/video-access/{id}'
 */
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/video-access/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\VideoAccessController::show
 * @see app/Http/Controllers/Api/VideoAccessController.php:44
 * @route '/api/video-access/{id}'
 */
show.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                }

    return show.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\VideoAccessController::show
 * @see app/Http/Controllers/Api/VideoAccessController.php:44
 * @route '/api/video-access/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Api\VideoAccessController::show
 * @see app/Http/Controllers/Api/VideoAccessController.php:44
 * @route '/api/video-access/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Api\VideoAccessController::show
 * @see app/Http/Controllers/Api/VideoAccessController.php:44
 * @route '/api/video-access/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Api\VideoAccessController::show
 * @see app/Http/Controllers/Api/VideoAccessController.php:44
 * @route '/api/video-access/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Api\VideoAccessController::show
 * @see app/Http/Controllers/Api/VideoAccessController.php:44
 * @route '/api/video-access/{id}'
 */
        showForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\Api\VideoAccessController::block
 * @see app/Http/Controllers/Api/VideoAccessController.php:64
 * @route '/api/video-access/{id}/block'
 */
export const block = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: block.url(args, options),
    method: 'post',
})

block.definition = {
    methods: ["post"],
    url: '/api/video-access/{id}/block',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\VideoAccessController::block
 * @see app/Http/Controllers/Api/VideoAccessController.php:64
 * @route '/api/video-access/{id}/block'
 */
block.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                }

    return block.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\VideoAccessController::block
 * @see app/Http/Controllers/Api/VideoAccessController.php:64
 * @route '/api/video-access/{id}/block'
 */
block.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: block.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Api\VideoAccessController::block
 * @see app/Http/Controllers/Api/VideoAccessController.php:64
 * @route '/api/video-access/{id}/block'
 */
    const blockForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: block.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Api\VideoAccessController::block
 * @see app/Http/Controllers/Api/VideoAccessController.php:64
 * @route '/api/video-access/{id}/block'
 */
        blockForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: block.url(args, options),
            method: 'post',
        })
    
    block.form = blockForm
const VideoAccessController = { index, show, block }

export default VideoAccessController
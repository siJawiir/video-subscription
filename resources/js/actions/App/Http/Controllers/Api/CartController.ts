import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\CartController::show
 * @see app/Http/Controllers/Api/CartController.php:20
 * @route '/api/cart'
 */
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/cart',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\CartController::show
 * @see app/Http/Controllers/Api/CartController.php:20
 * @route '/api/cart'
 */
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CartController::show
 * @see app/Http/Controllers/Api/CartController.php:20
 * @route '/api/cart'
 */
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Api\CartController::show
 * @see app/Http/Controllers/Api/CartController.php:20
 * @route '/api/cart'
 */
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Api\CartController::show
 * @see app/Http/Controllers/Api/CartController.php:20
 * @route '/api/cart'
 */
    const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Api\CartController::show
 * @see app/Http/Controllers/Api/CartController.php:20
 * @route '/api/cart'
 */
        showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Api\CartController::show
 * @see app/Http/Controllers/Api/CartController.php:20
 * @route '/api/cart'
 */
        showForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\Api\CartController::addItem
 * @see app/Http/Controllers/Api/CartController.php:49
 * @route '/api/cart/add'
 */
export const addItem = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: addItem.url(options),
    method: 'post',
})

addItem.definition = {
    methods: ["post"],
    url: '/api/cart/add',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\CartController::addItem
 * @see app/Http/Controllers/Api/CartController.php:49
 * @route '/api/cart/add'
 */
addItem.url = (options?: RouteQueryOptions) => {
    return addItem.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CartController::addItem
 * @see app/Http/Controllers/Api/CartController.php:49
 * @route '/api/cart/add'
 */
addItem.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: addItem.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Api\CartController::addItem
 * @see app/Http/Controllers/Api/CartController.php:49
 * @route '/api/cart/add'
 */
    const addItemForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: addItem.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Api\CartController::addItem
 * @see app/Http/Controllers/Api/CartController.php:49
 * @route '/api/cart/add'
 */
        addItemForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: addItem.url(options),
            method: 'post',
        })
    
    addItem.form = addItemForm
/**
* @see \App\Http\Controllers\Api\CartController::updateItem
 * @see app/Http/Controllers/Api/CartController.php:78
 * @route '/api/cart/update'
 */
export const updateItem = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateItem.url(options),
    method: 'post',
})

updateItem.definition = {
    methods: ["post"],
    url: '/api/cart/update',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\CartController::updateItem
 * @see app/Http/Controllers/Api/CartController.php:78
 * @route '/api/cart/update'
 */
updateItem.url = (options?: RouteQueryOptions) => {
    return updateItem.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CartController::updateItem
 * @see app/Http/Controllers/Api/CartController.php:78
 * @route '/api/cart/update'
 */
updateItem.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateItem.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Api\CartController::updateItem
 * @see app/Http/Controllers/Api/CartController.php:78
 * @route '/api/cart/update'
 */
    const updateItemForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateItem.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Api\CartController::updateItem
 * @see app/Http/Controllers/Api/CartController.php:78
 * @route '/api/cart/update'
 */
        updateItemForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateItem.url(options),
            method: 'post',
        })
    
    updateItem.form = updateItemForm
/**
* @see \App\Http\Controllers\Api\CartController::removeItem
 * @see app/Http/Controllers/Api/CartController.php:103
 * @route '/api/cart/remove'
 */
export const removeItem = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: removeItem.url(options),
    method: 'post',
})

removeItem.definition = {
    methods: ["post"],
    url: '/api/cart/remove',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\CartController::removeItem
 * @see app/Http/Controllers/Api/CartController.php:103
 * @route '/api/cart/remove'
 */
removeItem.url = (options?: RouteQueryOptions) => {
    return removeItem.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CartController::removeItem
 * @see app/Http/Controllers/Api/CartController.php:103
 * @route '/api/cart/remove'
 */
removeItem.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: removeItem.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Api\CartController::removeItem
 * @see app/Http/Controllers/Api/CartController.php:103
 * @route '/api/cart/remove'
 */
    const removeItemForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: removeItem.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Api\CartController::removeItem
 * @see app/Http/Controllers/Api/CartController.php:103
 * @route '/api/cart/remove'
 */
        removeItemForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: removeItem.url(options),
            method: 'post',
        })
    
    removeItem.form = removeItemForm
/**
* @see \App\Http\Controllers\Api\CartController::checkout
 * @see app/Http/Controllers/Api/CartController.php:115
 * @route '/api/cart/checkout'
 */
export const checkout = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkout.url(options),
    method: 'post',
})

checkout.definition = {
    methods: ["post"],
    url: '/api/cart/checkout',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\CartController::checkout
 * @see app/Http/Controllers/Api/CartController.php:115
 * @route '/api/cart/checkout'
 */
checkout.url = (options?: RouteQueryOptions) => {
    return checkout.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CartController::checkout
 * @see app/Http/Controllers/Api/CartController.php:115
 * @route '/api/cart/checkout'
 */
checkout.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: checkout.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Api\CartController::checkout
 * @see app/Http/Controllers/Api/CartController.php:115
 * @route '/api/cart/checkout'
 */
    const checkoutForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: checkout.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Api\CartController::checkout
 * @see app/Http/Controllers/Api/CartController.php:115
 * @route '/api/cart/checkout'
 */
        checkoutForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: checkout.url(options),
            method: 'post',
        })
    
    checkout.form = checkoutForm
const CartController = { show, addItem, updateItem, removeItem, checkout }

export default CartController
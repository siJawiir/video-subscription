import AuthController from './AuthController'
import VideoController from './VideoController'
import UserController from './UserController'
import CartController from './CartController'
import CustomerOrderController from './CustomerOrderController'
import VideoAccessController from './VideoAccessController'
import WatchSessionController from './WatchSessionController'
import VideoCategoryController from './VideoCategoryController'
import VideoTagController from './VideoTagController'
import OrderController from './OrderController'
import RedisTestController from './RedisTestController'
const Api = {
    AuthController: Object.assign(AuthController, AuthController),
VideoController: Object.assign(VideoController, VideoController),
UserController: Object.assign(UserController, UserController),
CartController: Object.assign(CartController, CartController),
CustomerOrderController: Object.assign(CustomerOrderController, CustomerOrderController),
VideoAccessController: Object.assign(VideoAccessController, VideoAccessController),
WatchSessionController: Object.assign(WatchSessionController, WatchSessionController),
VideoCategoryController: Object.assign(VideoCategoryController, VideoCategoryController),
VideoTagController: Object.assign(VideoTagController, VideoTagController),
OrderController: Object.assign(OrderController, OrderController),
RedisTestController: Object.assign(RedisTestController, RedisTestController),
}

export default Api
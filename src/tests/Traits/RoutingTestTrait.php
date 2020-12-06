<?php
declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Route;

trait RoutingTestTrait
{
    private $router;
    private $findRoute;

    public function initAssertRouting() {
        // RouteCollection 取得
        // note: \Tests\TestCase の CreatesApplication トレイトで Application が生成される際に、
        //       routes 以下の設定ファイルで定義された Route が Route ファサードにセットされる
        $routeCollection = Route::getRoutes();

        // Router生成
        $this->router = new \Illuminate\Routing\Router(new Dispatcher());

        // Router に RouteCollection をセット
        $this->router->setRoutes($routeCollection);

        // Routerインスタンス の protected メソッドにアクセスする為に Reflection
        $reflectionRouter = new \ReflectionClass(get_class($this->router));
        $this->findRoute = $reflectionRouter->getMethod('findRoute');
        $this->findRoute->setAccessible(true);
    }

    /**
     * 指定した uri に dispatch される Route を取得
     *
     * @param string $uri
     * @param string $method
     * @return \Illuminate\Routing\Route
     */
    private function findRoute(string $uri, string $method): \Illuminate\Routing\Route
    {
        // Request 生成
        $request = \Illuminate\Http\Request::create($uri, strtoupper($method));

        // Request に dispatch される Route を取得
        $route = $this->findRoute->invokeArgs($this->router, [$request]);

        return $route;
    }

    /**
     * 指定した uri に dispatch される actionName と routeName を assert
     *
     * @param string $uri
     * @param string $method
     * @param array $expected
     */
    public function assertDispatchedRoute(
        array $expected,
        string $method,
        string $uri
    ) {
        // dispatch される Route を取得
        $route = $this->findRoute($uri, $method);

        // assert
        $this->assertEquals(
            $expected['actionName'],
            $route->getActionName(),
            'リクエストに dispatch されるコントローラが期待値通りである事'
        );
        $this->assertEquals(
            $expected['routeName'],
            $route->getName(),
            'リクエストに dispatch されるルート名が期待値通りである事'
        );
    }

    /**
     * 指定した uri に適用される Middleware を assert
     *
     * @dataProvider AppliedMiddlewareTestDataProvider
     * @param array $expected
     * @param string $method
     * @param string $uri Route::get()等の第一引数で設定した$uri
     */
    public function assertAppliedMiddleware(
        array $expected,
        string $method,
        string $uri
    ) {
        // dispatch される Route を取得
        $route = $this->findRoute($uri, $method);

        // 適用される Middleware を取得
        $appliedMiddleware = $route->action['middleware'];

        // assert
        $this->assertCount(count($expected['middleware']), $appliedMiddleware);
        foreach ($expected['middleware'] as $middleware) {
            $this->assertTrue(in_array($middleware, $appliedMiddleware));
        }
    }
}

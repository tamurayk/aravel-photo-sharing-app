<?php
declare(strict_types=1);

namespace Test\Unit\app\Helpers;

use Illuminate\Http\Request;
use Tests\AppTestCase;

class SortUriTest extends AppTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function testSort()
    {
        $request = Request::create(
            'http://example.com/hoge',
            'GET',
            []
        );
        $args = [
            $request,
            'id', //orderColumn
        ];
        $result = sort_uri(...$args);
        $expected = 'http://example.com/hoge?column=id&direction=asc';
        $this->assertEquals($expected, $result);

        $request = Request::create(
            'http://example.com/hoge',
            'GET',
            []
        );
        $args = [
            $request,
            'id', //orderColumn
            'desc', //orderDirection
        ];
        $result = sort_uri(...$args);
        $expected = 'http://example.com/hoge?column=id&direction=desc';
        $this->assertEquals($expected, $result);

        $request = Request::create(
            'http://example.com/hoge',
            'GET',
            [
                'k1' => 'v1'
            ]
        );
        $args = [
            $request,
            'id', //orderColumn
        ];
        $result = sort_uri(...$args);
        $expected = 'http://example.com/hoge?k1=v1&column=id&direction=asc';
        $this->assertEquals($expected, $result);

        $request = Request::create(
            'http://example.com/hoge',
            'GET',
            [
                'k1' => 'v1',
                'k2' => 'v2',
                'column' => 'column1',
                'direction' => 'asc'
            ]
        );
        $args = [
            $request,
            'id', //orderColumn
        ];
        $result = sort_uri(...$args);
        $expected = 'http://example.com/hoge?k1=v1&k2=v2&column=id&direction=asc';
        $this->assertEquals($expected, $result);

        $request = Request::create(
            'http://example.com/hoge',
            'GET',
            [
                'k1' => 'v1',
                'k2' => 'v2',
                'column' => 'column1',
                'direction' => 'asc'
            ]
        );
        $args = [
            $request,
            'column1', //orderColumn
        ];
        $result = sort_uri(...$args);
        $expected = 'http://example.com/hoge?k1=v1&k2=v2&column=column1&direction=desc';
        $this->assertEquals($expected, $result);

        $request = Request::create(
            'http://example.com/hoge',
            'GET',
            [
                'k1' => 'v1',
                'k2' => 'v2',
                'foo' => 'column1',
                'bar' => 'asc'
            ]
        );
        $args = [
            $request,
            'column1', //orderColumn
            null, //orderDirection
            'foo', //columnKey
            'bar', //directionKey
        ];
        $result = sort_uri(...$args);
        $expected = 'http://example.com/hoge?k1=v1&k2=v2&foo=column1&bar=desc';
        $this->assertEquals($expected, $result);

        $request = Request::create(
            'http://example.com/hoge',
            'GET',
            [
                'k1' => 'v1',
                'k2' => 'v2',
                'foo' => 'column1',
                'bar' => 'asc'
            ]
        );
        $args = [
            $request,
            'column1', //orderColumn
            null, //orderDirection
        ];
        $result = sort_uri(...$args);
        $expected = 'http://example.com/hoge?k1=v1&k2=v2&foo=column1&bar=asc&column=column1&direction=asc';
        $this->assertEquals($expected, $result);
    }
}


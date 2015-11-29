<?php

namespace Spray\Serializer\Cache;

use PHPUnit_Framework_TestCase;

class ArrayCacheTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        
    }
    
    public function testNotCached()
    {
        $cache = new ArrayCache('Serializer');
        $this->assertFalse($cache->exists('foo'));
    }
    
    public function testCacheIsCached()
    {
        $cache = new ArrayCache('Serializer');
        $cache->save('foo', 'asdasdasda<sd');
        $this->assertTrue($cache->exists('foo'));
    }
    
    public function testLoadInstanceOfCached()
    {
        $cache = new ArrayCache('Serializer');
        $cache->save('Foo\Foo', '<?php namespace Foo; class FooSerializer {}');
        $this->assertInstanceOf(
            'Foo\FooSerializer',
            $cache->load('Foo\Foo')
        );
    }
}

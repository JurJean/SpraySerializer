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
        $cache = new ArrayCache();
        $this->assertFalse($cache->exists('foo'));
    }
    
    public function testCacheIsCached()
    {
        $cache = new ArrayCache();
        $cache->save('foo', 'asdasdasda<sd');
        $this->assertTrue($cache->exists('foo'));
    }
    
    public function testLoadInstanceOfCached()
    {
        $cache = new ArrayCache();
        $cache->save('Foo\Foo', '<?php namespace Foo; class FooSerializer {}');
        $this->assertInstanceOf(
            'Foo\FooSerializer',
            $cache->load('Foo\Foo')
        );
    }
}

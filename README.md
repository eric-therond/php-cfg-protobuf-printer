## Usage

```php
$traverser = new PHPCfg\Traverser();
$traverser->addVisitor(new PHPCfg\Visitor\Simplifier());
$traverser->traverse($script);
```

To dump the graph in protobuf format:
```php
$dumper = new ProtobufPrinter\Protobuf();
echo $dumper->printScript($script);
```

## Dev

Cli command to generate protobuf files:
```shell
protoc --proto_path=./ --php_out=./lib/ ./lib/ProtobufPrinter/specs.proto
```

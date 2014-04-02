# FIS静态资源统计脚本维护文档

## 背景说明

线上静态资源统计为FIS静态资源自动合并系统提供数据基础，针对FIS Plus提供标准采集脚本(Quickling在此基础上进行部分修改)，脚本嵌入到原有smarty插件中。为了便于维护管理，对修改点进行整理说明，同时也方便部分产品线进行特定的升级维护。

## 统计数据

产品线通过`require`、`widget`等方式加载静态资源，在这些插件中添加代码统计所使用的**js、css、tpl**文件。同时在**html插件**中提供接口让用户填写fid,采样sampleRate并自动采集模板名称数据。

## 日志格式

示例日志
`http://nsclick.baidu.com/u.gif?pid=242&v=2&fs=b458ed2,f2b7060&otherStr=b458ee2,f2b7061&page=pagelet/page/index.tpl&sid=1396350814&hash=98d905c4aa&fid=test`

字段说明

* `pid`：log平台统计分配ID,不可修改
* `fid`：fis产品线标识分配字符串
* `v`  ：统计版本
* `page`：模板名
* `fs`：首屏静态资源
* `otherStr`：非首屏静态资源
* `hash`：所有静态资源字符串对应的hash

##采集脚本说明

统计提供了一个统一的采集API脚本 **FISAutoPack.class.php**,接口说明如下：

* `setFid`：设置FIS分配产品线ID字符串
* `setSampleRate`：设置采样率
* `setPageName`：设置页面名称，为模板名
* `addStatic`：添加静态资源，参数为资源对应hash值
* `setFRender`：设置首屏完成。在此之前的资源认为是首屏资源
* `getCountUrl`：获取生成的发送数据js脚本

以下基于FIS Plus标准统计版本及Quickling版本分别介绍插件修改说明：

### **Quickling方式**

Quickling版统计脚本需要修改/添加的文件如下所示：

```bash
|---plugin //smarty 插件目录
|     |---lib 
|     |     └──FISAutoPack.class.php //添加通用统计API脚本
|     |     └──FISResource.class.php //提供获取静态资源hash接口
|     |     └──FISPagelet.class.php  //生成统计脚本
|     |---compiler.html.php //提供设置fid、sampleRate等支持
|     |---compiler.require.php //提供统计支持
|     |---compiler.widget.php //提供统计支持
```

#### 代码修改说明

1 **FISResource.class.php** 
添加`getStaticInfo`函数从map.json中读取资源信息，主要获取hash时使用

2 **FISPagelet.class.php** 
加载autopack，添加生成统计url函数`getCountUrl`，同时在两个地方调用以页面中生成统计的JS脚本

第一个地方：
`renderStatic`函数以下这行代码**前面**
```php
$html = str_replace(self::JS_SCRIPT_HOOK, $code . self::JS_SCRIPT_HOOK, $html);
```
第二个地方：
`display`函数以下这行代码**前面**
```php
$title = convertToUtf8(self::$_title);
```

3 **compiler.html.php插件**
此插件允许用户在{%html%}中填入fid、sampleRate进行初始化，同时获取模板名作为pageName。代码见插件中autopack标记的注释中间。

4 **compiler.require.php插件**
此插件在用户使用require加载资源的时候进行统计，代码见插件中autopack标记的注释中间。

5 **compiler.widget.php插件**
此插件在用户使用widget加载资源的时候进行统计，代码见插件中autopack标记的注释中间。














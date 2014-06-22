# Overview #

Simple **是**一个**结构清晰**、**层次分明**、**微型**的php框架。  

Simple **不是**一个试图满足**所有需求的怪物**。  

Simple **试图**解放你的思想，不被框架的条条框框所限制。  

Simple **试图**成为一个能让你自由发挥的框架。

Simple **试图**规范流程，而不是提供一个非常具体的实现。

##需求##


1. \>php5.3.0。
2. 熟悉php，熟悉面向对象编程。
3. 爱翻php手册。
4. 推荐IDE，[phpstorm](http://www.jetbrains.com/phpstorm/ “phpstrom主页”)



## 核心思想 ##


###1. 控制请求到响应的整个生命周期，并且对此按照合适的粒度进行分层，而不是着眼于提供庞大的功能。###

这部分的分层在项目的Cycle包下面，主要包含有

- Application
- Router
- Request
- Caller
- Response

执行的流程是从上到下，具体的**流程**为：

1. 所有的请求都会进入Application的run方法,整个请求到相应返回的生命周期都是在这个方法中进行的。
2. 在Application的run方法中会生成Router对象。
3. 根据Router对象，会生成Request对象，所有的请求参数都会包装在Request里面。
4. 把生成的Request对象传递给Caller对象，负责调用具体的接口(这里即可以是Controller，也可以实现为RPC)。
5. 然后接口生成Response对象。
6. 最后根据项目的需要，把Response对象转换为你所需要的格式，返回给客户端。

###2. 提供方便的数据操作模式(注:不提供复杂的ORM)。###

数据相关的操作都封装在了Model包下面，主要分为这个几个层次: 

1. Vo，实体类。
2. Dao，数据访问。
3. Driver，数据连接驱动。
4. Pool，连接池。

####Vo####
实体类，全称为Value Object。顾名思义，就是对数据库字段的一个映射。底层提供了两种类型的Vo,NoSQLVo和MySQLVo
项目中的实体类定义字段的时候必须定义为 ` proteced $_变量名称 `的格式，并且最好实现各个字段的getter和setter方法，这部分代码完全可以由IDE生成(注:推荐phpstrom)。

####Dao####
数据访问层，所有的数据访问都应该通过该层来访问，底层提供了两种类型的Dao，并且提供了一些针对单条信息的基础的操作方法。 如果底层的Dao层的功能不能满足你的要求，你自己自行扩展继承。

####Driver####
数据库驱动层，目前关系型数据库只支持PDO的方式，NoSQL类型的数据库只支持Memached。

####Pool####
连接池(针对一个请求到相应生命周期而言)，根据配置里面的标识获取数据库的连接，同一个标识多次获取，只会连接一次数据库。

###3. 日志功能###
系统底层采用的是开源的[monolog](https://github.com/Seldaek/monolog),系统包装了一个简单通用的方法来写入日志，封装在Log包下面，有一个叫LogUtil的工具类，有一个方法叫write。

###4. debug功能###
在php中，常用的debug方式有:

1. 打印输出,例如 echo，print_r，var_dump之类的打印函数。
2. 文件记录，把要知道的调试信息写入到一个文本中间。

这两种方式的缺点也，调试起来麻烦，调试完成后容易忘记去掉，由于采用的是系统函数，无法在非debug状态下关掉。
比较好的方式是，系统自定义一个调试方法，可以在非debug的情况下关掉。在Debug类下面封装了一个trace方法。专门用来调试，输出会默认输出到,Google Chrome的console面板下面，前提是你得安装一个叫做[Chrome Logger](https://chrome.google.com/webstore/detail/chrome-logger/noaneddfkdjfnfdakjjmocngnfkfehhd)的插件。

###5. Config，配置###
**只支持php数组形式的配置**。为什么不支持yaml和json或者其他格式的配置，无论你采用什么格式的配置文件，最终都必须解析转换为php的数组或者对象，而且由于每次常规的操作都会用到配置文件，都会去解析一次，这个太没必要了。
项目的配置相关都放在了Config目录下面，系统提供了一个ConfigManager::get的方法去获取对应的配置信息。
系统默认会现在项目配置中去找，若找到就直接返回，找不到，就到系统默认的配置中去查找，找到返回，如果找不到会抛出一个异常。

###6. Bootstrap，系统引导###

引导整个应用，提供服务。
主要有两个步骤

1. 初始化环境,使用init方法，这里面注册了对**警告的捕捉**，**未被捕捉异常的处理**，**自动加载文件**。
2. start，启动服务，注册Application对象。

###7.Application包###
由于框架本身只是提供一个规范和标准，没有提供具体的实现。所以Application包下面提供了两种不同类型应用的实现。

1. Game，适合于SNS游戏类型的应用。所有面向服务不需要输出html的应用都适用。

2. Web，Web网站类型应用。系统采用的模板引擎是[twig](http://twig.sensiolabs.org/ "twig模板引擎主页")









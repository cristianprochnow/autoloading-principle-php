# Importando arquivos

Quando se está mexendo com múltiplas classes no PHP, o modo mais simples de importar arquivos é por meio do uso de vários e vários `require_once` ao longo do trajeto.

Contudo, há uma solução mais simples para resolver essa pendência usabilidade do desenvolvedor. Vamos então a um exemplo.

## Referenciando classes

Ao referenciar classes que foram definidas com um `namespace` customizado, como abaixo, teremos então um erro caso não importemos os arquivos.

```php
use Bio\Animals\Cat;

$cat = new Cat();
```

Sem importar a classe, resultará em um erro como:

```
Uncaught Error: Class "Bio\Animals\Cat" not found in
```

Nós poderíamos simplesmente colocar um `require_once` para esse arquivo, no topo do `index.php` que resolveria. Contudo, não é uma boa solução a longo prazo, visto que conforme o número de classes aumenta, então o número de `require_once` também será maior.

Sendo assim, para resolver isso, podemos usar a função `spl_autoload_register` do PHP, que assim procura tudo aquilo que está sendo usado abaixo ou em qualquer lugar ao redor dele. Isto é, temos várias classes no projeto, contudo apenas precisamos da `Cat` nesse exemplo, então não faria sentido importar a `Dog` também.

Assim, essa função faz exatamente isso, pedindo apenas aquilo que **está sendo pedido** naquele contexto. Com isso, colocando a função dita acima, no trecho de código anterior, temos que:

```php
spl_autoload_register(function($class) {
  var_dump($class);
});

use Bio\Animals\Cat;

$cat = new Cat();
```

O conteúdo abaixo é retornado no `var_dump`:

```
'Bio\Animals\Cat'
```

Ou seja, achou apenas a classe que realmente está sendo usada, então agora apenas temos que traduzir o caminho do arquivo, para que o `namespace` corresponda com o caminho do arquivo (usamos um `namespace` customizado, mas poderíamos ter simplesmente colocado o caminho do arquivo com letras maiúsculas no início).

```php
spl_autoload_register(function($class) {
  $path = str_replace(
    'bio', 'src',
    lcfirst(str_replace('\\', '/', $class) . '.php')
  );

  require_once __DIR__ . '/' . $path;
});

use Bio\Animals\Cat;

$cat = new Cat();
```

E, então, com esse código acima, o erro para de aparecer, visto que a classe está sendo importada, e apenas o que realmente está sendo usado está sendo colocado em prática. Esse é o princípio mais fundamental de um recurso de _autoloading_ para o PHP.

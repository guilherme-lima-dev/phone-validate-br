# Validador de telefones do Brasil

Essa lib é um projeto que cobre 99% dos casos formatando e validando telefones brasileiros. Minha motivação para criar esse pacote veio de um projeto em que recebemos vários telefones de varias bases diferentes e cada um com um padrão diferente, esse projeto faz envio de mensagens via Whatsapp e SMS. Os telefones inválidos geravam muitos erros, então na tentativa de minimizar esses erros ao máximo criei essa lib que a principio só tem um métoco que realiza toda essa validação.

## Instalação

Use o composer para instalar

```bash
composer require glima/phone-validate-br
```

## Uso

```php
<?php
require "vendor/autoload.php"

use Glima\PhoneValidateBr\PhoneValidatorBR;

$phone = "+55(11) 91234-56455622";

$phoneValidator = PhoneValidatorBR::validate($phone);

echo "O telefone é válido? " . $phoneValidator->isValid; //false
echo "O telefone é: " . $phoneValidator->phone; //null
echo "Analise da validação: " . $phoneValidator->message; //O telefone não é válido!
```
## Sobre os testes

Foi implementado testes para os numeros de telefone e até então temos falha em um caso, que é quando é informado um DDD que inicia com 0 e tem 2 digitos e o nono digito é diferente de 9. Nesse caso não tive sucesso e ele reproduz um falso positivo, espero correções e auxilio da comunidade para resolver esse problema!

Segue a trilha de testes:
```
..............................F.............FF.........           55 / 55 (100%)
```
Os casos de falha são esses: 
- "(01) 2 6543-2109"
- "55 (01) 2 6543-2109"
- "055 (01) 2 6543-2109"

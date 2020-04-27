# apiagua

## Installation

Para fazer funcionar o api é necessário ter o ambinte php, servidor apache ou nginx
e tambem ter instado o composer na maquina.
Dentro da pasta do projeto rodar o comando: `composer install`

O token deve ser enviado no cabeçaho da requisição ex:`token:43dfd06266d8739c1d194c5fcc0b1999`

OBS: O banco de dados se encontra dentro da Pasta BD.

OBS2: As configurações da conexão encontra-se no arquivo Connection.php na Pasta config.

# Metodos HTTPs e Urls disponivel

| VERBOS  | RECURSOS |  FUCIONALIDADE  | ENTRADAS  | Token |
| ------------- | ------------- | ------------- | ------------- | ------------- |
| POST  | /users  | criar um usuario | email, password, name | não |
| GET  | /users  | listar todos usuario |  | sim |
| GET  | /users/:idusuario    | listar um usuario |  | sim |
| PUT  | /users/:idusuario  | atualizar um usuario | email, name, password | sim |
| DELETE  | /users/:idusuario  | deletar um usuario |  | sim |
| POST  | /users/:idusuario/drink  | adicionar quantidade de drink ao usuario | drink_ml | sim |
| POST  | /login  | fazer login |  email, password | sim |
| GET  | /drinkHistory/:iduser  | historico de drink do usuario |  | não |
| GET  | /ranking  | ranking do usuario com mais drink |  | não |

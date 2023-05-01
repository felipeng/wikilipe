[toc]
# NodeJS

 ![membercard.jpeg](data/imgs/membercard.jpeg) 
asdsad `echo $var` 
É um runtime para executar JavaScript local (sem browser)
* Muito utilizado em backend/frontend web
* NPM é um gerenciador de módulos/bibliotecas do NodeJS
* [pm2](ti/dev/nodejs/pm2) é um gerenciador de processos nodejs
* Exemplo da [BestBuy](https://github.com/omartinezpou/bbycaSRE)

## Instalação 
* `brew install nodejs npm`
* `apk add nodejs npm`

## Gerenciadores de Pacote

O gerenciador de pacote padrão é o NPM, porém existem algumas alternativas:

* [yarn](https://yarnpkg.com): é pra ser mais rápido que o `npm`
* [pnpm](https://pnpm.io): é pra ser mais rápido que o `npm` e o `yarn`

### NPM

NPM é um gerenciador de aplicação NodeJS, é possível:
* gerenciar dependências
* iniciar/parar aplicação

Comandos:
* `npm init`: gerar o arquivo `package.json` via wizard
* `npm install module`: instala um módulo no diretório local `/node_modules` (se usar a opção `-g` é global)
* `npm outdated`: verifica as versões instaladas e mostra quais estão desatualizadas
* `npm update`: atualiza todos os módulos
* `npm start|stop|test`: executa os comandos definidos no `script`
* `npm run`: mostra todas os comandos definidos do objeto `script` no `package.json`
* `npm run lint`: executa comandos definidos no `script` fora do padrão
* `npm exec` ou `npx`: para executar plugings

### package.json

`package.json` é um arquivo que contém as informações da aplicação como: 
* medatada: nome, versão, licenças, git repo, keywords
* dependências para ambiente de produção, desenvolvimento 
* instruções de start, stop, test

`package.json`:
```
{
  "name": "app",
  "version": "1.0.0",
  "description": "meu primeiro nodejs",
  "main": "app.js",
  "scripts": {
    "test": "node app.js",
    "start": "node app.js"
  },
  "repository": {
    "type": "git",
    "url": "http://www.blabla.com"
  },
  "keywords": [
    "hello",
    "world"
  ],
  "author": "felipe",
  "license": "ISC",
  "dependencies": {
    "express": "^4.17.2"
  },
  "devDependencies": {
    "express": "^4.17.2"
  }
}
```

```bash
npm run
Lifecycle scripts included in app@1.0.0:
  test
    node app.js
  start
    node app.js

npm start

> app@1.0.0 start
> node app.js

Server running at http://127.0.0.1:3000/
```

## Exemplo

`app.js`
```
const http = require('http');

const hostname = '127.0.0.1';
const port = 3000;

const server = http.createServer((req, res) => {
  res.statusCode = 200;
  res.setHeader('Content-Type', 'text/plain');
  res.end('Hello World');
});

server.listen(port, hostname, () => {
  console.log(`Server running at http://${hostname}:${port}/`);
});
```

Executar: `node app.js`
Testar: `curl localhost:3000`

### yarn

É pra ser mais rápido que o `npm` e também utiliza o `package.json`

### pnpm

É pra ser mais rapido que `npm` e `yarn`

## Lint

[ESLint](https://eslint.org) static analyses para código JavaScript é um lint para achar e arrumar problemas.

Instalação:
1. Instalação: `npm install eslint --save-dev`
1. Cria o arquivo de configuração (`.eslintrc.js`): `npm init @eslint/config`
1. Testar: ` npx eslint bestbuy.ca.js`

Utilizar:
1. Criar novos comandos no `package.json`
```json
...
  "scripts": {
    "lint-fix": "npx eslint routes/routes.js bestbuy.ca.js --fix",
    "lint": "npx eslint routes/routes.js bestbuy.ca.js",
   }
```
1. Executar: `npm run lint`

Observação:
* a opção `--fix` arruma automaticamente vários erros simples encontrados 

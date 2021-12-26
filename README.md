# Arranchamento Digital

####  Sistema para controle de arranchamento, com relatório e tiragem de faltas



 ## Alguns recursos

- Cadastro e Alteração de militares
- Arranchamento individual, por militar.
- Relatórios de arranchamentos em pdf 
- Calendário com a quantidade de militares arranchados
- Perfil de Furriel e Aprov, ambos conseguem arranchar e visualizar as datas que os militares estão arranchados por x dias.
- Cadastro de cardápio semanal, dividido por CB/SD e OF/ST/SGT

## Tecnologias utilizadas

- HTML5
- CSS3
- JavaScript (ECMAScript 2018)
- PHP 7.2+
- MariaDB 10.4.20
- [MPDF](https://mpdf.github.io/) - Utilizado para gerar os relatórios
- jQuery
- Composer


## Configurações
 Você pode alterar algumas variáveis globais e algumas opções do PHP.INI dentro do arquivo > `config.php`

Testei com um relatório COMPLETO (militares + quantitativo) de 6 meses
- Consulta no mysql: (37545 total, A consulta demorou 4,5228 segundos.)
- Total em páginas: 835
- Demorou cerca de 5 min para completar, e utilizou 2048mb de memória ram
- O tamanho total do arquivo PDF ficou de 5.6 mb

- `ini_set('max_execution_time', '600');` /// tempo máximo de execução em segundos
- `ini_set('pcre.backtrack_limit', '15200000');` /// tamanho máximo do html que o MPDF vai criar para gerar o PDF
- `ini_set('memory_limit', '2560M');` /// máximo de ram

OBS: `O relatório apenas do QUANTITATIVO não necessita  de tanto processamento.`

##### Não esqueça de:
- Habilitar o `extension=gd ` no php.ini.
- Executar `composer install` para instalar o MPDF.
- Dar permissão de escrita nas pastas `cardapios` e `relatorios`

## Licença
MIT

**Software gratúito!**

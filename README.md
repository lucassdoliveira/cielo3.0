# Módulo Integração Cielo API 3.0 Magento
Esse módulo para Magento em código aberto para contribuir com a comunidade.

O Módulo segue os padrões da documentação oficial Cielo, que você pode encontrar <a href="https://developercielo.github.io/Webservice-3.0/">aqui</a>.

Para auxiliar o cliente no Checkout foi inserido um plugin que simula o cartão de crédito enquanto o usuário digita os dados, visando uma maior conversão.

O Plugin utilizado e toda a documentação do funcionamento você pode encontrar <a href="https://github.com/jessepollak/card">aqui</a>

Abaixo segue um exemplo de preenchimento dos dados<p>
<a href="https://camo.githubusercontent.com/312e819c130acb5d17a5a8568c4ae6c315210dac/687474703a2f2f692e696d6775722e636f6d2f71473354656e4f2e676966" target="_blank"><img src="https://camo.githubusercontent.com/312e819c130acb5d17a5a8568c4ae6c315210dac/687474703a2f2f692e696d6775722e636f6d2f71473354656e4f2e676966" alt="card" data-canonical-src="http://i.imgur.com/qG3TenO.gif" style="max-width:100%;"></a>

Obs.: Lembre-se de instalar o módulo em ambiente de homologação, caso não tenha, faça um backup do seu projeto e do seu bd antes.

Baixe os arquivos, descompate e envie para a raiz do seu projeto<br>
caso você esteja utilizando algum template, verifique para enviar os arquivos para o local correto

"app\design\frontend\ [TEMPLATE] \ [TEMA] \layout"<br>
"app\design\frontend\ [TEMPLATE] \ [TEMA] \template"<br>
"skin\frontend\ [TEMPLATE] \ [TEMA] \css"<br>
"skin\frontend\ [TEMPLATE] \ [TEMA] \js"<br>

Checkout suportados
- Onestepcheckout6
- Venda Mais
- Padrão Magento

Se você utiliza outro padrão de checkout diferente dos informados acima, sera necessario customizar o arquivo
"app\design\frontend\ [TEMPLATE] \ [TEMA] \layout\Nitroecom_Cielo.xml" informando o block do seu checkout.

# ATENÇÃO: ESSE MÓDULO TEM O INTUITO DE AJUDAR A COMUNIDADE MAGENTO, NÃO NOS RESPONSABILIZAMOS POR EVENTUAIS PROBLEMAS QUE OCORRERAM DE COMPATIBILIDADE EM E-COMMERCE'S COM MODULOS DE TERCEIROS QUE GEREM CONFLITOS OU IMPEÇA O FUNCIONAMENTO DO PLUGIN. O MÓDULO É CODIGO ABERTO, CASO TENHAM PROBLEMAS PODEM FICAR A VONTADE DE POSTAR A SOLUÇÃO ENCONTRADA AQUI.

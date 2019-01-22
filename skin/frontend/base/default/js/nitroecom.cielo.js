var NitroecomCielo = {
    getParcelasHtml: function(valor, parcelas, juros, parcelassemjuros, valorminimo, descontoavista){
        
        var i = 1;
        var html = '';
        while(i <= parcelas){
            if(i ==1){
            //se for a primeira parcela, então verifica se tem desconto
                if(parseFloat(descontoavista) > 0){
                    var parcela = parseFloat(valor) - (valor * (parseFloat(descontoavista) / 100));
                    html = html + '<option value="' + i + '">à vista - R$ ' + parcela.toFixed(2).replace('.',',') + ' desconto de ' + descontoavista + '% </option>';
                } else {
                    var parcela = parseFloat(valor);
                    html = html + '<option value="' + i + '">à vista - R$ ' + parcela.toFixed(2).replace('.',',') + '</option>';
                }

            //verifica se a parcela é com ou sem juros
            } else if(i <= parcelassemjuros){
                //semjuros
                parcela = valor / i;
                if(parseFloat(parcela) < parseFloat(valorminimo)) {
                    break;
                }
                html = html + '<option value="' + i + '">' + i + 'x de R$ ' + parcela.toFixed(2).replace('.',',') + '</option>';
            } else {
                //com juros
                jurosconvertido =juros/100.00;
                valor_parcela = valor*jurosconvertido*Math.pow((1+jurosconvertido),i)/(Math.pow((1+jurosconvertido),i)-1);
                if(parseFloat(valor_parcela) < parseFloat(valorminimo)) {
                    break;
                }
                 html = html + '<option value="' + i + '">' + i + 'x de R$ ' + valor_parcela.toFixed(2).replace('.',',') + ' com juros de ' + juros + '%</option>';

            }
            i++;
        }
        return html;
    },

    getJurosAmount: function(valortotal,parcela,parcelasSemJuros,numeroMaximodeParcelas,juros_parcela){
        jurosAmount = 0;

        if(parcela > parcelasSemJuros){
            jurosconvertido = juros_parcela / 100;
            parcelacomjuros = valortotal*jurosconvertido*Math.pow((1+jurosconvertido),parcela)/(Math.pow((1+jurosconvertido),parcela)-1);
            parcelasemjuros = parseFloat(valortotal)/parseFloat(parcela);
            jurosAmount = parseFloat(parcelacomjuros) - parseFloat(parcelasemjuros);

            return jurosAmount*parcela;
        } else {
            return 0;
        }
    },

     getDiscountAmount: function(valortotal,parcela,desconotavista){

       if(parcela == 1){
         desconotavista = desconotavista/100;
         valorcomDesconto = valorTotal * (1 - desconotavista);
         valordodesconto = valorTotal - valorcomDesconto;
         return valordodesconto;
      }
    },


    getCreditCardLabel: function(cardNumber){

        // Visa: ^4[0-9]{12}(?:[0-9]{3})?$ All Visa card numbers start with a 4. New cards have 16 digits. Old cards have 13.
        // MasterCard: ^5[1-5][0-9]{14}$ All MasterCard numbers start with the numbers 51 through 55. All have 16 digits.
        // American Express: ^3[47][0-9]{13}$ American Express card numbers start with 34 or 37 and have 15 digits.
        // Diners Club: ^3(?:0[0-5]|[68][0-9])[0-9]{11}$ Diners Club card numbers begin with 300 through 305, 36 or 38. All have 14 digits. There are Diners Club cards that begin with 5 and have 16 digits. These are a joint venture between Diners Club and MasterCard, and should be processed like a MasterCard.
        // Discover: ^6(?:011|5[0-9]{2})[0-9]{12}$ Discover card numbers begin with 6011 or 65. All have 16 digits.
        // JCB: ^(?:2131|1800|35\d{3})\d{11}$ JCB cards beginning with 2131 or 1800 have 15 digits. JCB cards beginning with 35 have 16 digits.
        // http://www.regular-expressions.info/creditcard.html



        var regexVisa = /^4[0-9]{12}(?:[0-9]{3})?/;
        var regexMaster = /^5[1-5][0-9]{14}/;
        var regexAmex = /^3[47][0-9]{13}/;
        var regexDiners = /^3(?:0[0-5]|[68][0-9])[0-9]{11}/;
        var regexDiscover = /^6(?:011|5[0-9]{2})[0-9]{12}/;
        var regexJCB = /^(?:2131|1800|35\d{3})\d{11}/;

        if(regexVisa.test(cardNumber)){
            return 'visa';
        }
        if(regexMaster.test(cardNumber)){
            return 'master';
        }
        if(regexAmex.test(cardNumber)){
            return 'amex';
        }
        if(regexDiners.test(cardNumber)){
            return 'diners';
        }
        if(regexDiscover.test(cardNumber)){
            return 'discover';
        }
        if(regexJCB.test(cardNumber)){
            return 'jcb';
        }

        return '';
    }
}

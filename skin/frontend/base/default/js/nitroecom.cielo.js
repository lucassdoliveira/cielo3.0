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


        var regexVisa      = /^4[0-9]{12}(?:[0-9]{3})?/;
        var regexMaster    = /^5[^078][0-9]{14}/;
        var regexMasterBin2= /(222[1-8][0-9]{2}|2229[0-8][0-9]|22299[0-9]|22[3-9][0-9]{3}|2[3-6][0-9]{4}|27[01][0-9]{3}|2720[0-8][0-9]|27209[0-9])[0-9]{10}/;
        var regexAmex      = /^3[47][0-9]{13}/;
        var regexDiners    = /^3(?:0[0-5]|[68][0-9])[0-9]{11}/;
        var regexDiscover  = /^6(?:011|5[0-9]{2})[0-9]{12}/;
        var regexJCB       = /^(?:2131|1800|35\d{3})\d{11}/;
        var regexElo       = /^(40117[8-9]|431274|438935|451416|457393|45763[1-2]|506(699|7[0-6][0-9]|77[0-8])|509\d{3}|504175|627780|636297|636368|65003[1-3]|6500(3[5-9]|4[0-9]|5[0-1])|6504(0[5-9]|[1-3][0-9])|650(4[8-9][0-9]|5[0-2][0-9]|53[0-8])|6505(4[1-9]|[5-8][0-9]|9[0-8])|6507(0[0-9]|1[0-8])|65072[0-7]|6509(0[1-9]|1[0-9]|20)|6516(5[2-9]|[6-7][0-9])|6550([0-1][0-9]|2[1-9]|[3-4][0-9]|5[0-8]))/;
        var regexHipercard = /^(606282\d{10}(\d{3})?)|(3841\d{15})$/;
        var regexHiper     = /^(38|60)\d{11}(?:\d{3})?(?:\d{3})?$/;

        if(regexVisa.test(cardNumber))
            return 'visa';
        else if(regexMaster.test(cardNumber) || regexMasterBin2.test(cardNumber))
            return 'master';
        else if(regexAmex.test(cardNumber))
            return 'amex';
        else if(regexDiners.test(cardNumber))
            return 'diners';
        else if(regexDiscover.test(cardNumber))
            return 'discover';
        else if(regexJCB.test(cardNumber))
            return 'jcb';
        else if(regexElo.test(cardNumber))
            return 'elo';
        else if(regexHipercard.test(cardNumber))
            return 'hipercard';
        else if(regexHiper.test(cardNumber))
            return 'hiper';

        return '';
    }
}

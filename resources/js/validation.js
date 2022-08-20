import { extend } from "vee-validate";
import * as rules from "vee-validate/dist/rules";
import { messages } from "vee-validate/dist/locale/es.json";

extend("positive", (value) => {
    if (value >= 0) {
        return true;
    }

    return "El {_field_} debe se un numero positivo ";
});

extend("caja", {
    params: ["target"],
    validate(value, { target }) {
        return value <= target;
    },
    message: `El gasto no puede ser mayor al total en caja`,
});

extend("Imei", (value) => {
    if (value.length == 15) {
        return true;
    }

    return "El IMEI debe ser de 15 digitos ";
});

extend("Icc", (value) => {
    if (value.length == 19) {
        return true;
    }

    return "El ICC debe ser de 19 digitos ";
});

extend("curp", (value)=>{

    var re = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/,
        validado = value.match(re);
	
    if (!validado)  //Coincide con el formato general?
    	return false;
    
    //Validar que coincida el dígito verificador
    function digitoVerificador(curp17) {
        //Fuente https://consultas.curp.gob.mx/CurpSP/
        var diccionario  = "0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ",
            lngSuma      = 0.0,
            lngDigito    = 0.0;
        for(var i=0; i<17; i++)
            lngSuma = lngSuma + diccionario.indexOf(curp17.charAt(i)) * (18 - i);
        lngDigito = 10 - lngSuma % 10;
        if (lngDigito == 10) return 0;
        return lngDigito;
    }
  
    if (validado[2] != digitoVerificador(validado[1])) 
    	return false;
        
    return true; //Validado
});


extend("Luhn", (value) => {

    var luhnChk = (function (arr) {
        return function (ccNum) {
            var len = ccNum.length,
                bit = 1,
                sum = 0,
                val;

            while (len) {
                val = parseInt(ccNum.charAt(--len), 10);
                sum += (bit ^= 1) ? arr[val] : val;
            }

            return sum && sum % 10 === 0;
        };
    })([0, 2, 4, 6, 8, 1, 3, 5, 7, 9]);

    if(luhnChk(value)) return true;

    return "Icc no valido"
});

extend("Icc2", {
  params: ["target"],
  validate(value, { target }) {

      var icc1 = target.substring(5, target.length - 1);

      var icc2 = value.substring(5, value.length - 1);

      return Number(icc2) - Number(icc1) <= 1000;

  },
  message: "Solo se pueden calcular un maximo de 1000 chips",
});

Object.keys(rules).forEach((rule) => {
    extend(rule, {
        ...rules[rule], // copies rule configuration
        message: messages[rule], // assign message
    });
});

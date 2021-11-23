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

// extend("Luhn", (value) => {
//     // Accept only digits, dashes or spaces
//     if (/[^0-9-\s]+/.test(value)) return false;

//     // The Luhn Algorithm. It's so pretty.
//     let nCheck = 0,
//         bEven = false;
//     value = value.replace(/\D/g, "");

//     for (var n = value.length - 1; n >= 0; n--) {
//         var cDigit = value.charAt(n),
//             nDigit = parseInt(cDigit, 10);

//         if (bEven && (nDigit *= 2) > 9) nDigit -= 9;

//         nCheck += nDigit;
//         bEven = !bEven;
//     }

//     return nCheck % 10 == 0;
// });

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

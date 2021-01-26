import { extend } from "vee-validate";
import * as rules from 'vee-validate/dist/rules';
import { messages } from 'vee-validate/dist/locale/es.json';



extend("positive", (value) => {
    if (value >= 0) {
        return true;
    }

    return "El {_field_} debe se un numero positivo ";
});

extend('caja', {
  params: ['target'],
  validate(value, { target }) {
    return value <= target;
  },
  message: `El gasto no puede ser mayor al total en caja`
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
// extend("required", {
   
//     validate(value) {
//         return {
//             required: true,
//             valid: ['', null, undefined].indexOf(value) === -1
//           };
//         },
//         message: '{_field_} requerido',
//     // This rule reports the `required` state of the field.
//     computesRequired: true,
// });
Object.keys(rules).forEach(rule => {
    extend(rule, {
      ...rules[rule], // copies rule configuration
      message: messages[rule] // assign message
    });
  });
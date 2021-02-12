/**
 * Created by Axel DJAHA on 01/08/2018.
 *
 * Numeral.js config
 */
numeral.register('locale', 'fr', {
    delimiters: {
        thousands: '.',
        decimal: ','
    },
    abbreviations: {
        thousand: 'k',
        million: 'm',
        billion: 'b',
        trillion: 't'
    },
    ordinal : function (number) {
        return number === 1 ? 'er' : 'ème';
    },
    currency: {
        symbol: '€'
    }
});
// switch between locales
numeral.locale('fr');

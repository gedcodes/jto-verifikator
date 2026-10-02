const colors = require('tailwindcss/colors');
module.exports = {
    mode: 'jit',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: 'media', // or 'media' or 'class'
    theme: {
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            black: colors.black,
            white: colors.white,
            gray: colors.gray,
            emerald: colors.emerald,
            green: colors.green,
            red: colors.red,
            indigo: colors.indigo,
            yellow: colors.yellow,
            cyan: colors.cyan,
            teal: colors.teal,
            sky: colors.sky,
            blue: colors.blue,
            bluegrey: colors.slate,
            lightblue: colors.sky,
            rose: colors.rose,
            pink: colors.pink,
            orange: colors.orange,
            amber: colors.amber,
            lime: colors.lime,
            violet: colors.violet,
            purple: colors.purple,
            fuchsia: colors.fuchsia,

        },
        extend: {
            fontFamily: {
                'poppins': ['Poppins', 'sans-serif']
            },
            backgroundImage: {
                'login-bg': "/public/images/login-form-bg.png",
            }
        },
    },
    variants: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}

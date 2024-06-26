const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        './_pages/*.blade.php',
        './resources/views/**/*.blade.php',
        './vendor/hyde/framework/resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            typography: {
                DEFAULT: {
                    css: {
                        lineHeight: '1.5em',
                        maxWidth: '96ch',
                        h2: {
                            marginBottom: '0.75em',
                            marginTop: '1.5em',
                        },
                        a: {
                            color: colors.orange['600'],
                            '&:hover': {
                                color: colors.orange['500'],
                                textDecoration: 'underline',
                            },
                            textDecoration: 'none'
                        },
                        blockquote: {
                            backgroundColor: '#80808020',
                            borderLeftColor: '#d1d5db',
                            color: 'unset',
                            fontWeight: 500,
                            fontStyle: 'unset',
                            lineHeight: '1.25em',
                            paddingLeft: '0.75em',
                            paddingTop: '.25em',
                            paddingBottom: '.25em',
                            marginTop: '1em',
                            marginBottom: '1em',
                            p: {
                                paddingRight: '.25em',
                                marginTop: '.25em',
                                marginBottom: '.25em',
                            },
                            'p::before': {
                                content: 'unset',
                            },
                            'p::after': {
                                content: 'unset',
                            },
                        },
                        code: {
                            font: 'unset',
                            backgroundColor: '#80808033',
                            paddingLeft: '4px',
                            paddingRight: '4px',
                            marginLeft: '-2px',
                            marginRight: '1px',
                            borderRadius: '4px'
                        },
                        'code::before': {
                            content: 'unset',
                        },
                        'code::after': {
                            content: 'unset',
                        },
                        p: {
                            overflowWrap: 'break-word',
                            code: {
                                wordBreak: 'break-word',
                            }
                        },
                        pre: {
                            code: {
                                fontFamily: "'Fira Code Regular', Consolas, Monospace, 'Courier New'",
                            }
                        }
                    },
                },
                invert: {
                    css: {
                        a: {
                            color: colors.teal['500'],
                            '&:hover': {
                                color: colors.teal['400'],
                            },
                        },
                    },
                },
            },
            colors: {
                indigo: {
                    500: '#5956eb',
                }
            },
            fontFamily: {
                delius: ['Delius', 'sans-serif']
            }
        },
    },

    plugins: [
        require('@tailwindcss/typography')
    ],
};

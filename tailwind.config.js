import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#b91c1c',
                primaryDark: '#7f1d1d',
                accent: '#ffffff',
                cream: '#fff7ed',
                warm: '#fef2f2',
            },
            fontFamily: {
                classic: ['Georgia', 'Times New Roman', 'serif', ...defaultTheme.fontFamily.serif],
            },
        },
    },
    plugins: [],
};


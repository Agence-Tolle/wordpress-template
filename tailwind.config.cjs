module.exports = {
  content: ['./index.php', './app/**/*.php', './resources/**/*.{php,vue,js}'],
  theme: {
    extend: {
      fontFamily: {
        display: [
          '"DM Serif Display"',
          ...defaultTheme.fontFamily.serif,
        ],
        body: ['"Roboto"', ...defaultTheme.fontFamily.sans],
        awesome: ['"FontAwesome"'],
      },
      fontSize: {
        12: ['12px', '18px'],
        14: ['14px', '20px'],
        16: ['16px', '24px'],
        18: ['18px', '29px'],
        20: ['20px', '30px'],
        24: ['24px', '36px'],
        28: ['28px', '1.1'],
        30: ['30px', '1.1'],
        36: ['36px', '1.1'],
        48: ['48px', '1.1'],
        60: ['60px', '1.1'],
        74: ['74px', '1.1'],
        78: ['78px', '1.1'],
      },
      fontWeight: {
        light: '300',
        normal: '400',
        medium: '500',
        semibold: '600',
        bold: '700',
      },
      screens: {
        xs: '375px',
        sm: '640px',
        md: '768px',
        lg: '1024px',
        xl: '1280px',
        '2xl': '1600px',
        '3xl': '1920px',
      },
      colors: {
        white: '#FFFFFF',
        black: '#000000',
        red: {
          50: '#FFF4F4',
          100: '#FFDADA',
          200: '#FFB6B6',
          300: '#FD7575',
          400: '#FB3440',
          500: '#E4002B',
          600: '#CC0022',
          700: '#AB001C',
          800: '#870014',
          900: '#66000E',
          950: '#420008',
        },
        blue: {
          50: '#F4F7FD',
          100: '#E5ECFA',
          200: '#C6D4F1',
          300: '#A9BBE9',
          400: '#788BDB',
          500: '#4E5ACB',
          600: '#3E48B5',
          700: '#2E3697',
          800: '#20267B',
          900: '#000F5A',
          950: '#090A39',
        },
        green: {
          50: '#F5FFFD',
          100: '#EBFFFB',
          200: '#CFFFF5',
          300: '#B0FFEB',
          400: '#73FFD5',
          500: '#39FFBA',
          600: '#2EE69F',
          700: '#1FBF7A',
          800: '#149959',
          900: '#0B733C',
          950: '#044A21',
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
  ]
}

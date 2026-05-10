/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'selector', // این خط کلید معماست!
  theme: {
    content: [
      "../pages/*.html",
      "../*.html",
      "../pre-style/**/*.{js,ts,jsx,tsx}"
    ],
    theme: {
      extend: {

        borderRadius: {
          xl2: "20px",
          xl3: "28px"
        },

        boxShadow: {
          card: "0 10px 25px rgba(0,0,0,0.03),0 2px 5px rgba(0,0,0,0.02)",
          cardHover: "0 25px 40px rgba(59,130,246,0.15)"
        },

        animation: {
          fadeIn: "fadeIn .5s ease forwards"
        },

        keyframes: {
          fadeIn: {
            "0%": {
              opacity: "0",
              transform: "translateY(20px)"
            },
            "100%": {
              opacity: "1",
              transform: "translateY(0)"
            }
          }
        }

      }
    },
    plugins: []
  }
}

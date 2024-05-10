/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: ["class"],
  content: ["./assets/**/*.{js,jsx,ts,tsx}", "./templates/**/*.html.twig"],
  prefix: "",
  theme: {
    container: {
      center: true,
      padding: "2rem",
      screens: {
        "2xl": "1400px",
      },
    },
    extend: {
      keyframes: {
        "accordion-down": {
          from: { height: "0" },
          to: { height: "var(--radix-accordion-content-height)" },
        },
        "accordion-up": {
          from: { height: "var(--radix-accordion-content-height)" },
          to: { height: "0" },
        },
        blinkRed: {
          "0%, 100%": { backgroundColor: "#CC0000" },
          "50%": {
            backgroundColor: "#990000",
            boxShadow:
              "rgba(0, 0, 0, 0.2) 0 -1px 7px 1px, inset #990000 0 -1px 9px, rgba(204, 0, 0, 0.5) 0 2px 0",
          },
        },
      },
      animation: {
        "accordion-down": "accordion-down 0.2s ease-out",
        "accordion-up": "accordion-up 0.2s ease-out",
        blink: "blinkRed 0.7s infinite",
      },
    },
  },
  plugins: [require("tailwindcss-animate")],
};

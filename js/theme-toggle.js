/**
 * File theme-toggle.js.
 *
 * Handles toggling between light and dark themes.
 */
;(() => {
  // Check for saved theme preference or use the system preference
  const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)")
  const storedTheme = localStorage.getItem("theme")

  // Function to set theme
  function setTheme(theme) {
    if (theme === "dark") {
      document.body.classList.add("dark")
      localStorage.setItem("theme", "dark")
    } else {
      document.body.classList.remove("dark")
      localStorage.setItem("theme", "light")
    }
  }

  // Set initial theme
  if (storedTheme) {
    setTheme(storedTheme)
  } else {
    setTheme(prefersDarkScheme.matches ? "dark" : "light")
  }

  // Toggle theme when button is clicked
  const themeToggle = document.getElementById("theme-toggle")
  if (themeToggle) {
    themeToggle.addEventListener("click", () => {
      if (document.body.classList.contains("dark")) {
        setTheme("light")
      } else {
        setTheme("dark")
      }
    })
  }

  // Update theme when system preference changes
  prefersDarkScheme.addEventListener("change", (e) => {
    if (!localStorage.getItem("theme")) {
      setTheme(e.matches ? "dark" : "light")
    }
  })
})()


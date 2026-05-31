// Disable right-click
document.addEventListener("contextmenu", function (event) {
    event.preventDefault();
  });

  // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S, Ctrl+P
  document.addEventListener("keydown", function (event) {
    if (
      event.key === "F12" || 
      (event.ctrlKey && (event.key === "u" || event.key === "U")) || 
      (event.ctrlKey && event.shiftKey && (event.key === "I" || event.key === "i" || event.key === "J" || event.key === "j")) ||
      (event.ctrlKey && (event.key === "s" || event.key === "S" || event.key === "p" || event.key === "P"))
    ) {
      event.preventDefault();
    }
  });
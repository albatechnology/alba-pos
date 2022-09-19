function generatePDF() {
  const element = document.getElementById("invoice-POS");
  var opt = {
    filename: "myfile.pdf",
    // image: { type: "jpg", quality: 0.98 },
    html2canvas: { scale: 5 },
    jsPDF: { unit: "mm", format: [100, 250], orientation: "portrait" },
    floatPrecision: "smart",
  };
  html2pdf().from(element).set(opt).save();
}

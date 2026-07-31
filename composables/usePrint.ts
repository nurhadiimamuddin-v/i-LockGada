export const usePrint = () => {
  const printSurat = (title, dataFields) => {
    const printWindow = window.open('', '_blank');
    if (!printWindow) {
      alert('Gagal membuka pop-up cetak. Pastikan pop-up tidak diblokir browser.');
      return;
    }

    let rowsHtml = '';
    for (const [key, value] of Object.entries(dataFields)) {
      rowsHtml += `
        <tr>
          <td style="padding: 8px; width: 30%; border-bottom: 1px solid #ddd;"><strong>${key}</strong></td>
          <td style="padding: 8px; width: 70%; border-bottom: 1px solid #ddd;">${value || '-'}</td>
        </tr>
      `;
    }

    const htmlContent = `
      <html>
        <head>
          <title>Cetak Surat - ${title}</title>
          <style>
            body { font-family: 'Times New Roman', Times, serif; color: #000; padding: 40px; line-height: 1.6; }
            .header { text-align: center; border-bottom: 3px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
            .header img { width: 150px; }
            .header h1 { margin: 10px 0 5px 0; font-size: 24px; text-transform: uppercase; }
            .header p { margin: 0; font-size: 14px; }
            .content h2 { text-align: center; font-size: 18px; text-decoration: underline; margin-bottom: 30px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
            .footer { margin-top: 50px; text-align: right; }
            .footer .ttd { display: inline-block; text-align: center; width: 250px; }
            .footer .ttd-space { height: 100px; }
          </style>
        </head>
        <body>
          <div class="header">
            <h1>Pegadaian (Persero)</h1>
            <p>Sistem Pengelolaan Barang Jaminan (i-LockGada)</p>
            <p>Safety and Trusty</p>
          </div>
          <div class="content">
            <h2>SURAT BUKTI KETERANGAN BARANG</h2>
            <table>
              <tbody>
                ${rowsHtml}
              </tbody>
            </table>
            <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya dan menjadi bukti otentik transaksi pada sistem i-LockGada.</p>
          </div>
          <div class="footer">
            <div class="ttd">
              <p>Mengetahui,</p>
              <p>Petugas Pegadaian</p>
              <div class="ttd-space"></div>
              <p>_______________________</p>
            </div>
          </div>
          <script>
            window.onload = function() {
              window.print();
              setTimeout(() => { window.close(); }, 500);
            }
          </script>
        </body>
      </html>
    `;

    printWindow.document.open();
    printWindow.document.write(htmlContent);
    printWindow.document.close();
  };

  return { printSurat };
}
